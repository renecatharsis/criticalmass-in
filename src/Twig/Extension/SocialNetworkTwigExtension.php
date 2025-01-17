<?php declare(strict_types=1);

namespace App\Twig\Extension;

use Caldera\SocialNetworkBundle\Network\NetworkInterface;
use Caldera\SocialNetworkBundle\NetworkManager\NetworkManagerInterface;

class SocialNetworkTwigExtension extends \Twig_Extension
{
    /** @var NetworkManagerInterface */
    protected $networkManager;

    public function __construct(NetworkManagerInterface $networkManager)
    {
        $this->networkManager = $networkManager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getNetwork', [$this, 'getNetwork'], ['is_safe' => ['html']]),
        ];
    }

    public function getName(): string
    {
        return 'social_network_extension';
    }

    public function getNetwork(string $identifier): ?NetworkInterface
    {
        if (!$this->networkManager->hasNetwork($identifier)) {
            return null;
        }

        return $this->networkManager->getNetwork($identifier);
    }
}

