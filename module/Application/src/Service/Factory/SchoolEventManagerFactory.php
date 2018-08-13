<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\SchoolEventManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for SchoolEventManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SchoolEventManagerFactory{
    /**
     * This method creates the UserManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new SchoolEventManager($entityManager, $seasonManager);
    }
}

