<?php

namespace AppBundle\CityCycleRideGenerator;

use AppBundle\Entity\City;
use AppBundle\Entity\CityCycle;
use AppBundle\Entity\Ride;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class CityCycleRideGenerator
{
    /** @var int year */
    protected $year;

    /** @var int $month */
    protected $month;

    /** @var  \DateTime $dateTime */
    protected $dateTime;

    /** @var City $city */
    protected $city;

    /** @var array $rideList */
    protected $rideList = [];

    /** @var Doctrine $doctrine */
    protected $doctrine;

    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setCity(City $city): CityCycleRideGenerator
    {
        $this->city = $city;

        return $this;
    }

    public function setYear(int $year): CityCycleRideGenerator
    {
        $this->year = $year;

        return $this;
    }

    public function setMonth(int $month): CityCycleRideGenerator
    {
        $this->month = $month;

        return $this;
    }

    public function execute(): CityCycleRideGenerator
    {
        $this->rideList = [];

        if ($this->city->getTimezone()) {
            $timezone = new \DateTimeZone($this->city->getTimezone());
        } else {
            $timezone = new \DateTimeZone('Europe/Berlin');
        }

        $this->dateTime = new \DateTime(sprintf('%d-%d-01 00:00:00', $this->year, $this->month), $timezone);

        $cycles = $this->findCylces();

        foreach ($cycles as $cycle) {
            $ride = new Ride();
            $ride
                ->setCity($this->city)
            ;

            $ride = $this->calculateDate($cycle, $ride);
            $ride = $this->calculateTime($cycle, $ride);
            $ride = $this->setupLocation($cycle, $ride);

            $this->rideList[] = $ride;
        }

        return $this;
    }

    public function getList(): array
    {
        return $this->rideList;
    }

    protected function findCylces(): array
    {
        $startDateTime = $this->dateTime;
        $endDateTime = new \DateTimeImmutable(sprintf('%d-%d-%d 00:00:00', $this->year, $this->month, $startDateTime->format('t')));

        return $this->doctrine->getRepository(CityCycle::class)->findByCity($this->city, $startDateTime, $endDateTime);
    }

    protected function calculateDate(CityCycle $cityCycle, Ride $ride): Ride
    {
        $dateTime = clone $this->dateTime;

        $dayInterval = new \DateInterval('P1D');

        while ($dateTime->format('w') != $cityCycle->getDayOfWeek()) {
            $dateTime->add($dayInterval);
        }

        if ($cityCycle->getWeekOfMonth() > 0) {
            $weekInterval = new \DateInterval('P7D');

            $weekOfMonth = $this->city->getStandardWeekOfMonth();

            for ($i = 1; $i < $weekOfMonth; ++$i) {
                $dateTime->add($weekInterval);
            }
        } else {
            $weekInterval = new \DateInterval('P7D');

            while ($dateTime->format('m') == $this->month) {
                $dateTime->add($weekInterval);
            }

            $dateTime->sub($weekInterval);
        }

        $ride->setDateTime($dateTime);

        return $ride;
    }

    protected function calculateTime(CityCycle $cityCycle, Ride $ride): Ride
    {
        $time = $cityCycle->getTime();
        $intervalSpec = sprintf('PT%dH%dM', $time->format('H'), $time->format('i'));
        $timeInterval = new \DateInterval($intervalSpec);

        $dateTimeSpec = sprintf('%d-%d-%d 00:00:00', $ride->getDateTime()->format('Y'), $ride->getDateTime()->format('m'), $ride->getDateTime()->format('d'));
        $rideDateTime = new \DateTime($dateTimeSpec);
        $rideDateTime->add($timeInterval);

        $ride->setDateTime($rideDateTime);

        return $ride;
    }

    protected function setupLocation(CityCycle $cityCycle, Ride $ride): Ride
    {
        $ride
            ->setLatitude($cityCycle->getLatitude())
            ->setLongitude($cityCycle->getLongitude())
            ->setLocation($cityCycle->getLocation())
        ;

        return $ride;
    }

    public function isRideDuplicate(): bool
    {
        return false;
    }
}