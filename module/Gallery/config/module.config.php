<?php
namespace Gallery;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    
    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'gallery' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/gallery[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\GalleryController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    
    'controllers' => [
        'factories' => [
            Controller\GalleryController::class => Controller\Factory\GalleryControllerFactory::class,
        ],
        
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\GalleryController::class => [
                // Give access to "index" to anyone.
                    ['actions' => ['index'], 'allow' => '*'],
                // Give access to "addGalleryPost" actions to ADMIN only.
                    ['actions' => ['addgallerypost'], 'allow' => '+gallery.add'],
                // Give access to "getEditGallery" and "editGalleryPost" actions to ADMIN only.
                    ['actions' => ['geteditgallery', 'editgallerypost'], 'allow' => '+gallery.edit'],
                // Give access to "deletegallerypost" actions to ADMIN only.
                    ['actions' => ['deletegallerypost'], 'allow' => '+gallery.delete'],

            ],
            
        ]
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\GalleryBlogManager::class => Service\Factory\GalleryBlogManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'gallery' => __DIR__ . '/../view',
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