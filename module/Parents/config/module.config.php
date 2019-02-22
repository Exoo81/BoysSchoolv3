<?php

namespace Parents;

use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;


return [
    
    'router' => [
        'routes' => [
            'parents' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/parents[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ParentsController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    

    'controllers' => [
        'factories' => [
            Controller\ParentsController::class => Controller\Factory\ParentsControllerFactory::class,
        ],
    ],
    
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [
            Controller\ParentsController::class => [
                // Give access to "index", "getBookList", "getPolicy" to anyone.
                    ['actions' => ['index', 'getbooklist', 'getpolicy'], 'allow' => '*'],
                // Give access to "addParentsInformation" actions to ADMIN only.
                    ['actions' => ['addparentsinformation'], 'allow' => '+parentsInformation.add'],
                // Give access to "getEditParentsInformation", "editParentsInformation" and "deleteParentsInformation" actions to ADMIN only.
                    ['actions' => ['geteditparentsinformation', 'editparentsinformation', 'deleteparentsinformation'], 'allow' => '+parentsInformation.manage'],
                // Give access to "addMemberToParentsAssoc", "deleteParentsAssocMember", "activateParentsAssocMember", "getEditParentsAssocMeeting" and "editParentsAssocMeeting" actions to ADMIN and Parents_Assoc.
                    ['actions' => ['addmembertoparentsassoc', 'deleteparentsassocmember', 'activateparentsassocmember', 'geteditparentsassocmeeting', 'editparentsassocmeeting'], 'allow' => '+parentsAssoc.manage'],
                // Give access to "getAddBookListSelect", "addBookList" actions to ADMIN and Teachers
                    ['actions' => ['getaddbooklistselect', 'addbooklist'], 'allow' => '+bookList.add'],
                // Give access to "editBookList", "deleteBookList"  authorized users only.
                // access to the action with the option of using the defined permission in the index.phtml (parents) file and in the ParentsController
                    ['actions' => ['editbooklist', 'deletebooklist'], 'allow' => '@'],
                // Give access to "addEnrolment", "deleteEnrolment" actions to ADMIN only.
                    ['actions' => ['addenrolment', 'deleteenrolment'], 'allow' => '+enrolment.manage'],
                // Give access to "addPolicy", "editPolicy", "deletePolicy" actions to ADMIN only.
                    ['actions' => ['addpolicy', 'editpolicy', 'deletepolicy'], 'allow' => '+policies.manage'],
            ],
            
        ]
    ],
    
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    
    'service_manager' => [
        'factories' => [       
            Service\ParentsManager::class => Service\Factory\ParentsManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
        ],
    ],
    
    
    'view_manager' => [
        'template_path_stack' => [
            'parents' => __DIR__ . '/../view',
        ],
    ],
    
    // We register module-provided view helpers under this key.
    'view_helpers' => [
        'factories' => [
            View\Helper\CurrentPolicies::class => View\Helper\Factory\CurrentPoliciesFactory::class,
        ],
        'aliases' => [
            'currentPolicies' => View\Helper\CurrentPolicies::class,
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
