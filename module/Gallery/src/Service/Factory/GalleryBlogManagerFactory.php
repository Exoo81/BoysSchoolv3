<?php
namespace Gallery\Service\Factory;

use Interop\Container\ContainerInterface;
use Gallery\Service\GalleryBlogManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for GalleryBlogManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class GalleryBlogManagerFactory{
    /**
     * This method creates the BlogManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new GalleryBlogManager($entityManager, $seasonManager);
    }
}

