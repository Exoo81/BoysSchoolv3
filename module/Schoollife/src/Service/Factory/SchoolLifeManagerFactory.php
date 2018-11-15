<?php
namespace Schoollife\Service\Factory;

use Interop\Container\ContainerInterface;
use Schoollife\Service\SchoolLifeManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for SchoolLifeManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SchoolLifeManagerFactory{
    /**
     * This method creates the BlogManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new SchoolLifeManager($entityManager, $seasonManager);
    }
}

