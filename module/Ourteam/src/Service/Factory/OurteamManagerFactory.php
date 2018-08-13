<?php
namespace Ourteam\Service\Factory;

use Interop\Container\ContainerInterface;
use Ourteam\Service\OurteamManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for OurteamManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class OurteamManagerFactory{
    /**
     * This method creates the ParentsManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new OurteamManager($entityManager, $seasonManager);
    }
}
