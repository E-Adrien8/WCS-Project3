<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\FirewallMapInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppTwigExtension extends AbstractExtension implements GlobalsInterface
{
    private FirewallMapInterface $firewallMap;
    private RequestStack $requestStack;
    private string $googleMapJsKey;

    public function __construct(FirewallMapInterface $firewallMap, RequestStack $requestStack, string $googleMapJsKey)
    {
        $this->firewallMap = $firewallMap;
        $this->requestStack = $requestStack;
        $this->googleMapJsKey = $googleMapJsKey;
    }

    public function getGlobals(): array
    {
        if ($this->firewallMap instanceof FirewallMap) {
            $firewallName = $this->firewallMap->getFirewallConfig($this->requestStack->getCurrentRequest())->getName();
        }

        return [
            'firewall_name' => $firewallName ?? 'main',
            'google_map_js_key' => $this->googleMapJsKey,
        ];
    }
}
