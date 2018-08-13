<?php

namespace School;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\SchoolController::class => InvokableFactory::class,
        ],
    ],
    
    'router' => [
        'routes' => [
            'school' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/school[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\SchoolController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'school' => __DIR__ . '/../view',
        ],
    ],
];
