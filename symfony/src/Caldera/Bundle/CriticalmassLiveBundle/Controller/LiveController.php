<?php

namespace Caldera\Bundle\CriticalmassLiveBundle\Controller;

use Caldera\Bundle\CalderaBundle\Entity\City;
use Caldera\Bundle\CalderaBundle\Entity\Ride;
use Caldera\Bundle\CriticalmassSiteBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class LiveController extends AbstractController
{
    public function indexAction($name)
    {
        return $this->render('CalderaCriticalmassLiveBundle:Default:index.html.twig', array('name' => $name));
    }

    public function cityAction(Request $request, $citySlug)
    {
        /**
         * @var City $city
         */
        $city = $this->getCheckedCity($citySlug);

        /**
         * @var Ride $ride
         */
        $ride = $this->getRideRepository()->findCurrentRideForCity($city);

        $events = $this->getEventRepository()->findEventsForRide($ride);

        $this->getMetadata()
            ->setDescription('Live dabei: Schau dir an, wo sich die Critical Mass in '.$city->getCity().' gerade befindet!');

        return $this->render(
            'CalderaCriticalmassSiteBundle:Live:index.html.twig',
            array(
                'rides' => [$ride],
                'events' => $events
            )
        );
    }
}
