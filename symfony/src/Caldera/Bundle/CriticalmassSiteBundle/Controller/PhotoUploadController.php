<?php

namespace Caldera\Bundle\CriticalmassSiteBundle\Controller;

use Caldera\Bundle\CriticalmassCoreBundle\Image\ExifReader\DateTimeExifReader;
use Caldera\Bundle\CriticalmassCoreBundle\Image\PhotoGps\PhotoGps;
use Caldera\Bundle\CriticalmassModelBundle\Entity\Event;
use Caldera\Bundle\CriticalmassModelBundle\Entity\Photo;
use Caldera\Bundle\CriticalmassModelBundle\Entity\Ride;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * This controller does nothing but caring about photo upload.
 *
 * Class PhotoUploadController
 * @package Caldera\Bundle\CriticalmassSiteBundle\Controller
 */
class PhotoUploadController extends AbstractController
{
    public function uploadAction(Request $request, $citySlug, $rideDate = null, $eventSlug = null)
    {
        $ride = null;
        $event = null;

        if ($rideDate) {
            $ride = $this->getCheckedCitySlugRideDateRide($citySlug, $rideDate);
        } elseif ($eventSlug) {
            $event = $this->getEventRepository()->findOneBySlug($eventSlug);
        }

        if ($request->getMethod() == 'POST') {
            return $this->uploadPostAction($request, $ride, $event);
        } else {
            return $this->uploadGetAction($request, $ride, $event);
        }
    }

    protected function uploadGetAction(Request $request, Ride $ride = null, Event $event = null)
    {
        return $this->render('CalderaCriticalmassSiteBundle:PhotoUpload:upload.html.twig', [
            'ride' => $ride,
            'event' => $event
        ]);
    }

    protected function uploadPostAction(Request $request, Ride $ride = null, Event $event = null)
    {
        $em = $this->getDoctrine()->getManager();

        $photo = new Photo();

        $photo->setImageFile($request->files->get('file'));
        $photo->setUser($this->getUser());

        if ($ride) {
            $photo->setRide($ride);
            $photo->setCity($ride->getCity());
        }

        if ($event) {
            $photo->setEvent($event);
            $photo->setCity($event->getCity());
        }

        $em->persist($photo);
        $em->flush();

        /**
         * @var DateTimeExifReader $dter
         */
        $dter = $this->get('caldera.criticalmass.image.exifreader.datetime');

        $dateTime = $dter
            ->setPhoto($photo)
            ->execute()
            ->getDateTime();

        $photo->setDateTime($dateTime);

        $em->persist($photo);
        $em->flush();

        $track = null;

        if ($ride) {
            $track = $this->getTrackRepository()->findByUserAndRide($ride, $this->getUser());
        }

        if ($ride and $track) {
            /**
             * @var PhotoGps $pgps
             */
            $pgps = $this->get('caldera.criticalmass.image.photogps');

            $pgps
                ->setPhoto($photo)
                ->setTrack($track)
                ->execute();

            $em->merge($photo);
            $em->flush();
        }

        return new Response('foo');
    }
}
