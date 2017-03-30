<?php

namespace AppBundle\Controller\Photo;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use AppBundle\Entity\Ride;
use AppBundle\Entity\Track;
use AppBundle\Traits\ViewStorageTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotoController extends AbstractController
{
    use ViewStorageTrait;

    public function showAction(Request $request, string $citySlug, string $rideDate , int $photoId): Response
    {
        /** @var City $city */
        $city = $this->getCheckedCity($citySlug);

        /** @var Track $track */
        $track = null;

        /** @var Ride $ride */
        $ride = $this->getCheckedCitySlugRideDateRide($citySlug, $rideDate);

        if ($ride && $ride->getRestrictedPhotoAccess() && !$this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        /** @var Photo $photo */
        $photo = $this->getPhotoRepository()->find($photoId);

        $previousPhoto = $this->getPhotoRepository()->getPreviousPhoto($photo);
        $nextPhoto = $this->getPhotoRepository()->getNextPhoto($photo);

        $this->countView($photo);

        if ($ride && $photo->getUser()) {
            /** @var Track $track */
            $track = $this->getTrackRepository()->findByUserAndRide($ride, $photo->getUser());
        }

        return $this->render(
            'AppBundle:Photo:show.html.twig',
            [
                'photo' => $photo,
                'nextPhoto' => $nextPhoto,
                'previousPhoto' => $previousPhoto,
                'city' => $city,
                'ride' => $ride,
                'track' => $track,
            ]
        );
    }

    public function ajaxphotoviewAction(Request $request): Response
    {
        $photoId = $request->get('photoId');

        /**
         * @var Photo $photo
         */
        $photo = $this->getPhotoRepository()->find($photoId);

        if ($photo) {
            $this->countView($photo);
        }

        return new Response(null);
    }
}
