<?php
namespace Archive\Service\Factory;

use Interop\Container\ContainerInterface;
use Archive\Service\ArchiveManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for ArchiveManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ArchiveManagerFactory{
    /**
     * This method creates the ArchiveManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new ArchiveManager($entityManager, $seasonManager);
    }
}

