<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\City;
use App\Entity\Ride;
use App\Entity\Thread;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findByCrawled(bool $crawled, int $limit = null): array
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where($qb->expr()->eq('p.crawled', ':crawled'))
            ->setParameter('crawled', $crawled);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getPostsForRide(Ride $ride): array
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('p')
            ->where($builder->expr()->eq('p.ride', ':ride'))
            ->setParameter('ride', $ride)
            ->andWhere($builder->expr()->eq('p.enabled', ':enabled'))
            ->setParameter('enabled', true)
            ->addOrderBy('p.dateTime', 'ASC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function countPostsForCityRides(City $city): int
    {
        $builder = $this->createQueryBuilder('p');

        $builder
            ->select('COUNT(p.id)')
            ->join('p.ride', 'ride')
            ->where($builder->expr()->eq('ride.city', ':city'))
            ->setParameter('city', $city);

        $query = $builder->getQuery();

        return (int) $query->getSingleScalarResult();
    }

    public function getPostsForCityRides(City $city)
    {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->join('post.ride', 'ride');

        $builder->where($builder->expr()->eq('ride.city', $city->getId()));


        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function findPostsForThread(Thread $thread)
    {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->where($builder->expr()->eq('post.thread', $thread->getId()));
        $builder->andWhere($builder->expr()->eq('post.enabled', 1));
        $builder->addOrderBy('post.dateTime', 'ASC');

        $query = $builder->getQuery();

        return $query->getResult();
    }

    public function findRecentChatMessages($limit = 25)
    {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->where($builder->expr()->eq('post.enabled', 1));
        $builder->andWhere($builder->expr()->eq('post.chat', 1));

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('post.dateTime', 'DESC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function findForTimelineThreadPostCollector(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $limit = null
    ) {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->join('post.thread', 'thread');

        $builder->where($builder->expr()->eq('post.enabled', 1));
        $builder->andWhere($builder->expr()->isNotNull('post.thread'));
        $builder->andWhere($builder->expr()->neq('post', 'thread.firstPost'));

        if ($startDateTime) {
            $builder->andWhere($builder->expr()->gte('post.dateTime',
                '\'' . $startDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($endDateTime) {
            $builder->andWhere($builder->expr()->lte('post.dateTime',
                '\'' . $endDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('post.dateTime', 'DESC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function findForTimelineBlogPostCommentCollector(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $limit = null
    ) {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->where($builder->expr()->eq('post.enabled', 1));
        $builder->andWhere($builder->expr()->isNotNull('post.blogPost'));

        if ($startDateTime) {
            $builder->andWhere($builder->expr()->gte('post.dateTime',
                '\'' . $startDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($endDateTime) {
            $builder->andWhere($builder->expr()->lte('post.dateTime',
                '\'' . $endDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('post.dateTime', 'DESC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function findForTimelineRideCommentCollector(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $limit = null
    ) {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->where($builder->expr()->eq('post.enabled', 1));
        $builder->andWhere($builder->expr()->isNotNull('post.ride'));

        if ($startDateTime) {
            $builder->andWhere($builder->expr()->gte('post.dateTime',
                '\'' . $startDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($endDateTime) {
            $builder->andWhere($builder->expr()->lte('post.dateTime',
                '\'' . $endDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('post.dateTime', 'DESC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }

    public function findForTimelinePhotoCommentCollector(
        \DateTime $startDateTime = null,
        \DateTime $endDateTime = null,
        $limit = null
    ) {
        $builder = $this->createQueryBuilder('post');

        $builder->select('post');

        $builder->where($builder->expr()->eq('post.enabled', 1));
        $builder->andWhere($builder->expr()->isNotNull('post.photo'));

        if ($startDateTime) {
            $builder->andWhere($builder->expr()->gte('post.dateTime',
                '\'' . $startDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($endDateTime) {
            $builder->andWhere($builder->expr()->lte('post.dateTime',
                '\'' . $endDateTime->format('Y-m-d H:i:s') . '\''));
        }

        if ($limit) {
            $builder->setMaxResults($limit);
        }

        $builder->addOrderBy('post.dateTime', 'DESC');

        $query = $builder->getQuery();

        $result = $query->getResult();

        return $result;
    }
}

