<?php

namespace Criticalmass\Bundle\AppBundle\Repository;

use Criticalmass\Bundle\AppBundle\Entity\Ride;
use Doctrine\ORM\EntityRepository;

class FacebookRidePropertiesRepository extends EntityRepository
{
    public function findByRide(Ride $ride)
    {
        $builder = $this->createQueryBuilder('frp');

        $builder->select('frp');

        $builder->where($builder->expr()->eq('frp.ride', $ride->getId()));

        $builder->orderBy('frp.createdAt');

        $query = $builder->getQuery();

        return $query->getResult();
    }
}

