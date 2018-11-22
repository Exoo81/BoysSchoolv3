<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    
    // Session configuration.
    'session_config' => [
        'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
        'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).        
    ],
    // Session manager configuration.
    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    
    // Cache configuration.
    'caches' => [
        'FilesystemCache' => [
            'adapter' => [
                'name'    => Filesystem::class,
                'options' => [
                    // Store cached data in this directory.
                    'cache_dir' => './data/cache',
                    // Store cached data for 1 hour.
                    'ttl' => 60*60*1 
                ],
            ],
            'plugins' => [
                [
                    'name' => 'serializer',
                    'options' => [                        
                    ],
                ],
            ],
        ],
    ],
    
//    NEW DB (local)
//    'doctrine' => [
//        'connection' => [
//            'orm_default' => [
//                'driverClass' => PDOMySqlDriver::class,
//                'params' => [
//                    'host'     => 'localhost',                    
//                    'user'     => 'root',
//                    'password' => '',
//                    'dbname'   => 'oranmoreboysnsdb',
//                    'port' => '3307',
//                ]
//            ],            
//        ],        
//    ],
     
// DB (Marcin cloudways)    
//    'doctrine' => [
//        'connection' => [
//            'orm_default' => [
//                'driverClass' => PDOMySqlDriver::class,
//                'params' => [
//                    'host'     => '209.97.142.163',                    
//                    'user'     => 'rccnwgwvhb',
//                    'password' => 'eQ52JYspr3',
//                    'dbname'   => 'rccnwgwvhb',
//                    'port' => '3306',
//                ]
//            ],            
//        ],        
//    ],
    
   

// DB (OranmoreBoysNS cloudways)    
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class, 
                'params' => [
                    'host'     => '178.62.8.86',                    
                    'user'     => 'bfnvauxgwy',
                    'password' => '3aFnHmu8uq',
                    'dbname'   => 'bfnvauxgwy',
                    'port' => '3306',
                ]
            ],            
        ],        
    ],
        

];




