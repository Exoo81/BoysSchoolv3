<?php

namespace Classes;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;

return [
    
    'router' => [
        'routes' => [
            'classes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/classes[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ClassesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'classblog' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/classblog[/:action[/:id[/:color]]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\ClassBlogController::class,
                        'action'        => 'index',
                    ],
                ],
                
            ],

        ],
    ],
    
    'controllers' => [
        'factories' => [
            Controller\ClassesController::class =>Controller\Factory\ClassesControllerFactory::class,
            Controller\ClassBlogController::class =>Controller\Factory\ClassBlogControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\ClassesController::class => [
                // Give access to "index" to anyone.
                    ['actions' => ['index'], 'allow' => '*'],
                // Give access to "getselectoptions" and "addClassBlog" actions to ADMIN only.
                    ['actions' => ['getselectoptions', 'addclassblog'], 'allow' => '+classes.add'],
                // Give access to "geteditclassblog" and "editclassblog" actions to authorized users only.
                // access to the action with the option of using the defined permission in the index.phtml (classes) file and in the ClassesController
                    ['actions' => ['geteditclassblog', 'editclassblog'], 'allow' => '@'],
                // Give access to "deleteblog" actions to ADMIN only.
                    ['actions' => ['deleteblog'], 'allow' => '+classes.delete'],  
            ],
            Controller\ClassBlogController::class => [
                // Give access to "index" to anyone.
                    ['actions' => ['index'], 'allow' => '*'],
                // Give access to "addpost" action to authorized users only.
                // access to the action with the option of using the defined permission in the index.phtml (class-blog) file and in the ClassBlogController
                    ['actions' => ['addpost'], 'allow' => '@'],
                // Give access to "geteditpost" and "editpost" actions to authorized users only.
                // access to the action with the option of using the defined permission in the index.phtml (class-blog) file and in the ClassBlogController
                    ['actions' => ['geteditpost', 'editpost'], 'allow' => '@'],
                // Give access to "deletepost" actions to ADMIN only.
                    //['actions' => ['deletepost'], 'allow' => '+post.delete'],
                // Give access to "deletepost"  authorized users only.
                // access to the action with the option of using the defined permission in the index.phtml (class-blog) file and in the ClassBlogController
                    ['actions' => ['deletepost'], 'allow' => '@'],
            ],
        ]
    ],
    
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\ClassesManager::class => Service\Factory\ClassesManagerFactory::class,
            Service\PostManager::class => Service\Factory\PostManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'classes' => __DIR__ . '/../view',
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

