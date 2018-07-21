<?php declare(strict_types=1);

namespace App\Criticalmass\Router\DelegatedRouter;

use App\EntityInterface\RouteableInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface DelegatedRouterInterface
{
    public function generate(RouteableInterface $routeable, string $routeName = null, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string;

    public function supports(RouteableInterface $routeable): bool;
}