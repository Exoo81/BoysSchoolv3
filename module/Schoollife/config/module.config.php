<?php
namespace Schoollife;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'schoollife' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/schoollife[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\SchoolLifeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        'factories' => [
//            Controller\SchoolLifeController::class => InvokableFactory::class,
            Controller\SchoolLifeController::class => Controller\Factory\SchoolLifeControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the Schoollife module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\SchoolLifeController::class => [
                // Give access to "index" and getschoollife to anyone.
                    ['actions' => ['index', 'getschoollife'], 'allow' => '*'],  
                // Give access to "editSchoolLife", "deleteSchoolLife"  actions to ADMIN only.
                    ['actions' => ['editschoollife', 'deleteschoollife'], 'allow' => '+schoolLife.manage'],
            ],
        ]
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\SchoolLifeManager::class => Service\Factory\SchoolLifeManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'schoollife' => __DIR__ . '/../view',
        ],
    ],
//    
//    // We register module-provided view helpers under this key.
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


