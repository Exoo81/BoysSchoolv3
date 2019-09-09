<?php
namespace Archive;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'archive' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/archive[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ArchiveController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'archiveGallery' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/archive/gallery[/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ArchiveController::class,
                        'action'     => 'gallery',
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        'factories' => [
            Controller\ArchiveController::class => Controller\Factory\ArchiveControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\ArchiveController::class => [
                // Give access to "index"
                    ['actions' => ['index'], 'allow' => '*'], 
                    ['actions' => ['gallery'], 'allow' => '*'],
            ],
        ]
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\ArchiveManager::class => Service\Factory\ArchiveManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'archive' => __DIR__ . '/../view',
        ],
    ],
    
    // We register module-provided view helpers under this key.
//    'view_helpers' => [
//        'factories' => [
//            View\Helper\CurrentContact::class => View\Helper\Factory\CurrentContactFactory::class,
//        ],
//        'aliases' => [
//            'currentContact' => View\Helper\CurrentContact::class,
//        ],
//    ],
    
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];


