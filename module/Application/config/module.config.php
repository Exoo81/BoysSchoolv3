<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;


use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            
        ],
    ],
    
    'controllers' => [
        'factories' => [
            Controller\IndexController::class =>Controller\Factory\IndexControllerFactory::class,    
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\IndexController::class => [
                // Give access to "index", "Add subscriptions" actions
                // to anyone.
                    ['actions' => ['index', 'addsubscriptions', 'getevents', 'getcalendar', 'getevent'], 'allow' => '*'],
                // Give access to "addNews" actions to ADMIN only.
                    ['actions' => ['addnews'], 'allow' => '+news.add'],
                // Give access to edit news to ADMIN only
                    ['actions' => ['geteditnews', 'editnews'], 'allow' => '+news.edit'],
                // Give access to delete news to ADMIN only
                    ['actions' => ['deletenews'], 'allow' => '+news.delete'],
                // Give access to edit Welcome message to ADMIN only
                    ['actions' => ['geteditwelcomemsg', 'editwelcomemsg'], 'allow' => '+welcomemsg.edit'],
                // Give access to edit About us message to ADMIN only
                    ['actions' => ['getaboutus', 'editaboutus'], 'allow' => '+aboutus.edit'],
                // Give access to "addNewsletter" actions to ADMIN only.
                    ['actions' => ['addnewsletter'], 'allow' => '+newsletter.add'],
                // Give access to delete Newsletter to ADMIN only
                    ['actions' => ['deletenewsletter'], 'allow' => '+newsletter.delete'],
                // Give access to "addEvent" actions to ADMIN only.
                    ['actions' => ['addevent'], 'allow' => '+event.add'],
                // Give access to edit event to ADMIN and Parents Assoc. only
                    ['actions' => ['editevent'], 'allow' => '+event.edit'],
                // Give access to delete event to ADMIN and Parents Assoc. only
                    ['actions' => ['deleteevent'], 'allow' => '+event.delete'],
                // Give access to add award to ADMIN  only
                    ['actions' => ['addouraward'], 'allow' => '+award.add'],
                // Give access to add award to ADMIN  only
                    ['actions' => ['deleteouraward'], 'allow' => '+award.delete'],      
            ],
        ]
    ],
    
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    'service_manager' => [
        'factories' => [
//            Service\NavManager::class => Service\Factory\NavManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
        ],
    ],
    
    'service_manager' => [
        'factories' => [
            
            
            Service\AboutUsManager::class => Service\Factory\AboutUsManagerFactory::class,
            Service\NewsManager::class => Service\Factory\NewsManagerFactory::class,
            Service\NewsletterManager::class => Service\Factory\NewsletterManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
            Service\SchoolEventManager::class => Service\Factory\SchoolEventManagerFactory::class,
            Service\SubscriptionManager::class => Service\Factory\SubscriptionManagerFactory::class,
            Service\WelcomeMsgManager::class => Service\Factory\WelcomeMsgManagerFactory::class,
            Service\OurAwardsManager::class => Service\Factory\OurAwardsManagerFactory::class,
        ],
    ],
    
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => ['ViewJsonStrategy',],
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
