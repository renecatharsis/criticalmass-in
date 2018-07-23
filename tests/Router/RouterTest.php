<?php declare(strict_types=1);

namespace Tests\Router;

use AppBundle\Criticalmass\Router\ObjectRouter;
use AppBundle\Entity\City;
use AppBundle\Entity\CitySlug;
use AppBundle\Entity\Ride;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RouterTest extends KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
    }

    protected function getRouter(): ObjectRouter
    {
        return static::$kernel->getContainer()->get(ObjectRouter::class);
    }

    public function test1(): void
    {
        $citySlug = new CitySlug();
        $city = new City();

        $citySlug
            ->setSlug('testcity')
            ->setCity($city);

        $city->setMainSlug($citySlug);

        $route = $this->getRouter()->generate($city, 'caldera_criticalmass_city_show');

        $this->assertEquals('/testcity', $route);
    }

    public function testCity(): void
    {
        $citySlug = new CitySlug();
        $city = new City();

        $citySlug
            ->setSlug('testcity')
            ->setCity($city);

        $city->setMainSlug($citySlug);

        $route = $this->getRouter()->generate($city);

        $this->assertEquals('/testcity', $route);
    }

    public function testRide(): void
    {
        $citySlug = new CitySlug();
        $city = new City();

        $citySlug
            ->setSlug('testcity')
            ->setCity($city);

        $city->setMainSlug($citySlug);

        $ride = new Ride();
        $ride
            ->setDateTime(new \DateTime('2018-01-01'))
            ->setCity($city);

        $city->addRide($ride);

        $route = $this->getRouter()->generate($ride);

        $this->assertEquals('/testcity/2018-01-01', $route);
    }
}
