<?php

namespace Album;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\AlbumController::class => InvokableFactory::class,
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'album' => [ // The name of the route is ‘album’
                'type'    => Segment::class, // The type of the route is ‘segment’ => allows us to specify placeholders in the URL pattern (route) that will be mapped to named parameters in the matched route
                'options' => [
                    'route' => '/album[/:action[/:id]]', // The route is /album[/:action[/:id]] which will match any URL that starts with /album
                    'constraints' => [ // The constraints section allows us to ensure that the characters within a segment are as expected
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*', // we have limited actions to starting with a letter and then subsequent characters only being alphanumeric, underscore, or hyphen
                        'id'     => '[0-9]+', // We also limit the id to digits
                    ],
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],
];