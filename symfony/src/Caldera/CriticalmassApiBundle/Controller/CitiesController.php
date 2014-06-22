<?php

namespace Caldera\CriticalmassApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Caldera\CriticalmassCoreBundle\Utility as Utility;
use Caldera\CriticalmassCoreBundle\Entity as Entity;

class CitiesController extends Controller
{
    public function getbyslugAction($citySlug)
    {
        $citySlug = $this->getDoctrine()->getRepository('CalderaCriticalmassCoreBundle:CitySlug')->findOneBySlug($citySlug);

        if (empty($citySlug))
        {
            throw $this->createNotFoundException('This city is not registered.');
        }

        $city = $citySlug->getCity();

        $response = new Response();
        $response->setContent(json_encode(array(
            'city' => array(
                'id' => $city->getId(),
                'city' => $city->getCity(),
                'title' => $city->getTitle(),
                'description' => $city->getDescription(),
                'slug' => $city->getMainSlug()->getSlug()
             )
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function listallAction()
    {
        $cities = $this->getDoctrine()->getRepository('CalderaCriticalmassCoreBundle:City')->findBy(array(), array('city' => 'ASC'));

        $citiesResult = array();

        foreach ($cities as $city)
        {
            $cityResultArray = array(
                'id' => $city->getId(),
                'city' => $city->getCity(),
                'title' => $city->getTitle(),
                'description' => $city->getDescription(),
                'url' => $city->getUrl(),
                'facebook' => $city->getFacebook(),
                'twitter' => $city->getTwitter(),
                'slug' => $city->getMainSlug()->getSlug(),
                'latitude' => $city->getLatitude(),
                'longitude' => $city->getLongitude()
            );

            $citiesResult[$city->getMainSlug()->getSlug()] = $cityResultArray;
        }

        $response = new Response();
        $response->setContent(json_encode(array(
            'cities' => $citiesResult
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
