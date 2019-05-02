<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use App\Entity\Photo;
use App\Entity\Ride;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class PhotoRepository extends EntityRepository
{
    public function getPreviousPhoto(Photo $photo): ?Photo
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->eq('p.ride', ':ride'))
            ->setParameter('ride', $photo->getRide())
            ->andWhere($builder->expr()->lt('p.dateTime', ':dateTime'))
            ->setParameter('dateTime', $photo->getDateTime())
            ->andWhere($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false)
            ->addOrderBy('p.dateTime', 'DESC')
            ->setMaxResults(1);

        $query = $builder->getQuery();

        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function getNextPhoto(Photo $photo): ?Photo
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->eq('p.ride', ':ride'))
            ->setParameter('ride', $photo->getRide())
            ->andWhere($builder->expr()->gt('p.dateTime', ':dateTime'))
            ->setParameter('dateTime', $photo->getDateTime())
            ->andWhere($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false)
            ->addOrderBy('p.dateTime', 'ASC')
            ->setMaxResults(1);

        $query = $builder->getQuery();

        $result = $query->getOneOrNullResult();

        return $result;
    }

    public function findRidesWithPhotoCounterByUser(User $user): array
    {
        $builder = $this->createQueryBuilder('photo');

        $builder
            ->select('photo')
            ->addSelect('ride')
            ->addSelect('city')
            ->addSelect('COUNT(photo)')
            ->where($builder->expr()->eq('photo.deleted', ':deleted'))
            ->setParameter('deleted', false)
            ->andWhere($builder->expr()->eq('photo.user', ':user'))
            ->setParameter('user', $user)
            ->groupBy('photo.ride')
            ->join('photo.ride', 'ride')
            ->join('ride.city', 'city')
            ->orderBy('ride.dateTime', 'desc');

        $query = $builder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function findPhotosWithoutExifData(): array
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->isNull('p.exifExposure'))
            ->andWhere($builder->expr()->isNull('p.exifAperture'))
            ->andWhere($builder->expr()->isNull('p.exifIso'))
            ->andWhere($builder->expr()->isNull('p.exifFocalLength'))
            ->andWhere($builder->expr()->isNull('p.exifCamera'))
            ->orderBy('p.dateTime', 'asc');

        $query = $builder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function findGeocodeablePhotos(int $limit = 50, ?bool $emptyLocationOnly = false): array
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->isNotNull('p.latitude'))
            ->andWhere($builder->expr()->isNotNull('p.longitude'))
            ->orderBy('p.dateTime', 'asc')
            ->setMaxResults($limit);

        if ($emptyLocationOnly) {
            $builder->andWhere($builder->expr()->isNull('p.location'));
        }

        $query = $builder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    /**
     * @deprecated
     */
    public function findRidesForGallery(City $city = null): array
    {
        $builder = $this->createQueryBuilder('photo');

        $builder
            ->select('photo')
            ->addSelect('ride')
            ->addSelect('city')
            ->addSelect('COUNT(photo)')
            ->addSelect('featuredPhoto')
            ->where($builder->expr()->eq('photo.deleted', 0));

        if ($city) {
            $builder
                ->andWhere($builder->expr()->eq('photo.city', ':city'))
                ->setParameter('city', $city);
        }

        $builder
            ->join('photo.ride', 'ride')
            ->join('ride.city', 'city')
            ->leftJoin('ride.featuredPhoto', 'featuredPhoto')
            ->orderBy('ride.dateTime', 'desc')
            ->groupBy('ride');

        $query = $builder->getQuery();
        $result = $query->getResult();

        $galleryResult = [];

        foreach ($result as $row) {
            $ride = $row[0]->getRide();
            $counter = $row[1];

            $key = $ride->getDateTime()->format('Y-m-d') . '_' . $ride->getId();

            $galleryResult[$key]['ride'] = $ride;
            $galleryResult[$key]['counter'] = $counter;
        }

        return $galleryResult;
    }

    /**
     * @deprecated
     */
    public function findRidesWithPhotoCounter(City $city = null)
    {
        $builder = $this->createQueryBuilder('photo');

        $builder
            ->select('photo')
            ->addSelect('COUNT(photo)')
            ->where($builder->expr()->eq('photo.deleted', ':deleted'))
            ->setParameter('deleted', false);

        if ($city) {
            $builder
                ->andWhere($builder->expr()->eq('photo.city', ':city'))
                ->setParameter('city', $city);
        }

        $builder
            ->groupBy('photo.ride')
            ->join('photo.ride', 'ride')
            ->orderBy('ride.dateTime', 'desc');

        $query = $builder->getQuery();
        $result = $query->getResult();

        $rides = array();
        $counter = array();

        /**
         * @var Photo $photo
         */
        foreach ($result as $row) {
            /**
             * @var Ride $ride
             */
            $ride = $row[0]->getRide();
            $key = $ride->getDateTime()->format('Y-m-d') . '_' . $ride->getId();

            $rides[$key] = $ride;
            $counter[$key] = $row[1];
        }

        return [
            'rides' => $rides,
            'counter' => $counter
        ];
    }

    public function buildQueryPhotosByRide(Ride $ride): QueryBuilder
    {
        $builder = $this->createQueryBuilder('p');

        $builder->select('p')
            ->where($builder->expr()->eq('p.ride', ':ride'))
            ->setParameter('ride', $ride)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false)
            ->addOrderBy('p.dateTime', 'ASC');

        return $builder;
    }

    public function findPhotosByRide(Ride $ride): array
    {
        $builder = $this->buildQueryPhotosByRide($ride);

        return $builder->getQuery()->getResult();
    }

    public function countPhotosByRide(Ride $ride): int
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('COUNT(p)')
            ->where($builder->expr()->eq('p.ride', ':ride'))
            ->setParameter('ride', $ride)
            ->andWhere($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false);

        $query = $builder->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function buildQueryPhotosByUserAndRide(User $user, Ride $ride): Query
    {
        $builder = $this->createQueryBuilder('p');

        $builder->select('p')
        ->where($builder->expr()->eq('p.ride', ':ride'))
        ->setParameter('ride', $ride)
        ->andWhere($builder->expr()->eq('p.user', ':user'))
        ->setParameter('user', $user)
        ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
        ->setParameter('deleted', false)
        ->addOrderBy('p.dateTime', 'ASC');

        return $builder->getQuery();
    }

    public function findPhotosByUserAndRide(User $user, Ride $ride): array
    {
        $query = $this->buildQueryPhotosByUserAndRide($user, $ride);

        return $query->getResult();
    }

    public function findSomePhotos($limit = 16, $maxViews = 15, City $city = null): array
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->addSelect('RAND() as HIDDEN rand')
            ->where($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false);

        if ($maxViews) {
            $builder
                ->andWhere($builder->expr()->lte('p.views', ':maxViews'))
                ->setParameter('maxViews', $maxViews);
        }

        if ($city) {
            $builder
                ->andWhere($builder->expr()->eq('p.city', ':city'))
                ->setParameter('city', $city);
        }

        $builder
            ->addOrderBy('rand')
            ->setMaxResults($limit);

        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function findForTimelineRidePhotoCollector(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $limit = null
    ) {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('deleted', false);

        if ($startDateTime) {
            $builder
                ->andWhere($builder->expr()->gte('p.creationDateTime', ':startDateTime'))
                ->setParameter('startDateTime', $startDateTime);
        }

        if ($endDateTime) {
            $builder
                ->andWhere($builder->expr()->lte('p.creationDateTime', ':endDateTime'))
                ->setParameter('endDateTime', $endDateTime);
        }

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('p.creationDateTime', 'DESC');

        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function countByUser(User $user): int
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('COUNT(p)')
            ->where($builder->expr()->eq('p.user', ':user'))
            ->andWhere($builder->expr()->eq('p.enabled', ':enabled'))
            ->andWhere($builder->expr()->eq('p.deleted', ':deleted'))
            ->setParameter('user', $user)
            ->setParameter('enabled', true)
            ->setParameter('deleted', false);

        $query = $builder->getQuery();

        return (int) $query->getSingleScalarResult();
    }
}

