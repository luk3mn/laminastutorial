<?php

namespace Album;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    // The ModuleManager will call getConfig() automatically for us
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}