<?php
namespace Classes\Service\Factory;

use Interop\Container\ContainerInterface;
use Classes\Service\ClassesManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for ClassesManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ClassesManagerFactory{
    /**
     * This method creates the BlogManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new ClassesManager($entityManager, $seasonManager);
    }
}

