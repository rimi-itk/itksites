<?php

namespace App\Traits;

use App\Entity\Website;

trait WebsitePHPContainer
{
    protected function getPHPContainer(Website $website)
    {
        foreach ($website->getContainers() as $id => $container) {
            if (preg_match('/php/', $container['container']['Image'] ?? '')) {
                return $container;
            }
        }

        return null;
    }
}
