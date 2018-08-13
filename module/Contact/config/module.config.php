<?php
namespace Contact;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'contact' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/contact[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],


    'controllers' => [
        'factories' => [
            Controller\ContactController::class => Controller\Factory\ContactControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\ContactController::class => [
                // Give access to "index", "sendMessage" to anyone.
                    ['actions' => ['index', 'sendmessage'], 'allow' => '*'],  
                // Give access to "getEditContactInfo" and "editContact" actions to ADMIN only.
                    ['actions' => ['geteditcontactinfo', 'editcontact'], 'allow' => '+contact.edit'],
            ],
        ]
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\ContactManager::class => Service\Factory\ContactManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'contact' => __DIR__ . '/../view',
        ],
    ],
    
    // We register module-provided view helpers under this key.
    'view_helpers' => [
        'factories' => [
            View\Helper\CurrentContact::class => View\Helper\Factory\CurrentContactFactory::class,
        ],
        'aliases' => [
            'currentContact' => View\Helper\CurrentContact::class,
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


