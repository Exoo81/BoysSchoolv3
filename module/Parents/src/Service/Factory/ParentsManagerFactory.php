<?php
namespace Parents\Service\Factory;

use Interop\Container\ContainerInterface;
use Parents\Service\ParentsManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for ParentsManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ParentsManagerFactory{
    /**
     * This method creates the ParentsManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new ParentsManager($entityManager, $seasonManager);
    }
}

