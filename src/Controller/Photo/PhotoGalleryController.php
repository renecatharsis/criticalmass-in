<?php declare(strict_types=1);

namespace App\Controller\Photo;

use App\Controller\AbstractController;
use App\Entity\City;
use App\Entity\Photo;
use App\Entity\Ride;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Flagception\Bundle\FlagceptionBundle\Annotations\Feature;

/**
 * @Feature("photos")
 */
class PhotoGalleryController extends AbstractController
{
    /**
     * @ParamConverter("ride", class="App:Ride")
     */
    public function galleryAction(Request $request, PaginatorInterface $paginator, Ride $ride): Response
    {
        if ($ride && $ride->getRestrictedPhotoAccess() && !$this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $query = $this->getPhotoRepository()->buildQueryPhotosByRide($ride);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            32
        );

        return $this->render('PhotoGallery/gallery_list.html.twig', [
            'ride' => $ride,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Feature("photos")
     */
    public function userlistAction(UserInterface $user = null): Response
    {
        $result = $this->getPhotoRepository()->findRidesWithPhotoCounterByUser($user);

        return $this->render('PhotoGallery/user_list.html.twig', [
            'result' => $result,
        ]);
    }

    /**
     * @Feature("photos")
     */
    public function examplegalleryAction(): Response
    {
        $photos = $this->getPhotoRepository()->findSomePhotos(32);

        $cityList = [];

        /** @var Photo $photo */
        foreach ($photos as $photo) {
            /** @var City $city */
            $city = $photo->getRide()->getCity();
            $citySlug = $city->getSlug();

            $cityList[$citySlug] = $city;
        }

        shuffle($cityList);

        return $this->render('PhotoGallery/example_gallery.html.twig', [
            'photos' => $photos,
            'cities' => $cityList,
        ]);
    }
}
