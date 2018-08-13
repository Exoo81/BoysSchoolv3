<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\SeasonManager;




/**
 * This is the factory class for SeasonManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SeasonManagerFactory{
    /**
     * This method creates the SeasonManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

                        
        return new SeasonManager($entityManager);
    }
}

