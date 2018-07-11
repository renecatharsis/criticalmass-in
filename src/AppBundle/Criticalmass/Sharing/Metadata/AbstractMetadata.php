<?php declare(strict_types=1);

namespace AppBundle\Criticalmass\Sharing\Metadata;

use AppBundle\Criticalmass\Router\ObjectRouterInterface;
use AppBundle\Criticalmass\Sharing\Annotation\Route;
use AppBundle\Criticalmass\Sharing\Annotation\RouteParameter;
use AppBundle\Criticalmass\Sharing\Annotation\Shorturl;
use AppBundle\Criticalmass\Sharing\ShareableInterface\Shareable;
use Caldera\YourlsApiManager\YourlsApiManager;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractMetadata implements MetadataInterface
{
    /** @var ObjectRouterInterface $router */
    protected $router;

    /** @var AnnotationReader $annotationReader */
    protected $annotationReader;

    /** @var YourlsApiManager $yourlsApiManager */
    protected $yourlsApiManager;

    /** @var Doctrine $doctrine */
    protected $doctrine;

    public function __construct(ObjectRouterInterface $router, AnnotationReader $annotationReader, YourlsApiManager $yourlsApiManager, Doctrine $doctrine)
    {
        $this->router = $router;
        $this->annotationReader = $annotationReader;
        $this->yourlsApiManager = $yourlsApiManager;
        $this->doctrine = $doctrine;
    }

    protected function generateShareUrl(Shareable $shareable): string
    {
        return $this->router->generate($shareable, null, [], RouterInterface::ABSOLUTE_URL);
    }

    protected function checkShorturl(Shareable $shareable): ?string
    {
        if ($permalinkKeyword = $this->getShorturl($shareable)) {
            return $permalinkKeyword;
        }

        $shorturl = $this->yourlsApiManager->createShorturl($this->generateShareUrl($shareable), $this->getShareTitle($shareable));

        $this->setShorturl($shareable, $shorturl);

        $this->doctrine->getManager()->flush();

        return $shorturl;
    }

    protected function getRouteName(Shareable $shareable): string
    {
        $reflectionClass = new \ReflectionClass($shareable);
        $routeAnnotation = $this->annotationReader->getClassAnnotation($reflectionClass, Route::class);

        return $routeAnnotation->getRoute();
    }

    protected function getRouteParameter(Shareable $shareable): array
    {
        $parameter = [];

        $reflectionClass = new \ReflectionClass($shareable);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $parameterAnnotation = $this->annotationReader->getPropertyAnnotation($property, RouteParameter::class);

            if ($parameterAnnotation) {
                $getMethodName = sprintf('get%s', ucfirst($property->getName()));

                if (!$reflectionClass->hasMethod($getMethodName)) {
                    continue;
                }

                $value = $shareable->$getMethodName();

                $parameter[$parameterAnnotation->getName()] = $value;
            }
        }

        return $parameter;
    }

    protected function getShorturlPropertyName(Shareable $shareable): ?string
    {
        $reflectionClass = new \ReflectionClass($shareable);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $key => $property) {
            $permalinkKeywordAnnotation = $this->annotationReader->getPropertyAnnotation($property, Shorturl::class);

            if ($permalinkKeywordAnnotation) {
                return $property->getName();
            }
        }

        return null;
    }
}
