<?php

namespace Album;

// Add these import statements: Using ServiceManager to configure the table gateway and inject into the AlbumTable
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    // The ModuleManager will call getConfig() automatically for us
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    // This method returns an array of factories that are all merged together by the ModuleManager before passing them to the ServiceManager
    public function getServiceConfig()
    {
        return [
            'factories' => [ // The factory for Album\Model\AlbumTable uses the ServiceManager to create an Album\Model\AlbumTableGateway service representing a TableGateway to pass to its constructo
                Model\AlbumTable::class => function($container) {
                    $tableGateway = $container->get(Model\AlbumTableGateway::class);
                    return new Model\AlbumTable($tableGateway);
                },
                Model\AlbumTableGateway::class => function ($container) { // We also tell the ServiceManager that the AlbumTableGateway service is created by fetching a Laminas\Db\Adapter\AdapterInterface implementation (also from the ServiceManager) and using it to create a TableGateway object
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    // we'll create it in our Module class, only this time, under a new method, Album\Module::getControllerConfig()
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AlbumController::class => function($container) {
                    return new Controller\AlbumController(
                        $container->get(Model\AlbumTable::class)
                    );
                },
            ],
        ];
    }
}