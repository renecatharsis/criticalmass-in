<?php

namespace Caldera\Bundle\CriticalmassSiteBundle\Controller;

use Caldera\Bundle\CriticalmassModelBundle\Entity\Ride;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends AbstractController
{
    public function citystatisticAction(Request $request, $citySlug)
    {
        $city = $this->getCheckedCity($citySlug);

        $rides = $this->getRideRepository()->findRidesForCity($city);

        $this->getMetadata()->setDescription('Critical-Mass-Statistiken aus '.$city->getCity().': Teilnehmer, Fahrtdauer, Fahrtlänge, Touren');

        return $this->render(
            'CalderaCriticalmassSiteBundle:Statistic:citystatistic.html.twig',
            [
                'city' => $city,
                'rides' => $rides
            ]
        );
    }

    public function overviewAction(Request $request)
    {
        $region = $this->getRegionRepository()->find(3);
        
        $rides = $this->getRideRepository()->findRidesInRegion($region);

        $cities = [];

        $rideMonths = [];

        /**
         * @var Ride $ride
         */
        foreach ($rides as $ride) {
            $cities[$ride->getCity()->getSlug()] = $ride->getCity();

            $rideMonths[$ride->getDateTime()->format('Y-m')] = $ride->getDateTime()->format('Y-m');
        }

        rsort($rideMonths);

        $this->getMetadata()->setDescription('Critical-Mass-Statistiken: Teilnehmer, Fahrtdauer, Fahrtlänge, Touren');

        return $this->render(
            'CalderaCriticalmassSiteBundle:Statistic:overview.html.twig',
            [
                'cities' => $cities,
                'rides' => $rides,
                'rideMonths' => $rideMonths
            ]
        );
    }
}
