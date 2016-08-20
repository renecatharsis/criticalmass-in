<?php

namespace Caldera\Bundle\CriticalmassBlogBundle\Controller;

use Caldera\Bundle\CriticalmassSiteBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DefaultController extends AbstractController
{
    public function indexAction(Request $request)
    {
        $posts = $this->getBlogPostRepository()->findBy([], ['dateTime' => 'DESC']);

        return $this->render(
            'CalderaCriticalmassBlogBundle:Default:index.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    public function viewAction(Request $request, $slug)
    {
        $post = $this->getBlogPostRepository()->findOneBySlug($slug);

        if (!$post) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'CalderaCriticalmassBlogBundle:Default:view.html.twig',
            [
                'post' => $post
            ]
        );
    }
}