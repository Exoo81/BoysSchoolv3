<?php

namespace Ourteam;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;


return [
    
    'router' => [
        'routes' => [
            'ourteam' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/ourteam[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\OurteamController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    

    'controllers' => [
        'factories' => [
            Controller\OurteamController::class => Controller\Factory\OurteamControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\OurteamController::class => [
                // Give access to "index" to anyone.
                    ['actions' => ['index'], 'allow' => '*'],
                // Give access to "deleteOurTeam", "deleteManagment", "addOurTeamMember", "addManagementMember", "activateOurTeam" actions to ADMIN only.
                    ['actions' => ['deleteourteam', 'deletemanagment', 'addourteammember', 'addmanagementmember', 'activateourteam'], 'allow' => '+ourteam.manage'],
            ],
            
        ]
    ],
    
//    // This key stores configuration for RBAC manager.
//    'rbac_manager' => [
//        'assertions' => [Service\RbacAssertionManager::class],
//    ],
    
    'service_manager' => [
        'factories' => [       
            Service\OurteamManager::class => Service\Factory\OurteamManagerFactory::class,
//            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'ourteam' => __DIR__ . '/../view',
        ],
    ],
   
    
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