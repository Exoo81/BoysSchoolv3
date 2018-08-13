<?php
namespace Ourteam\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Ourteam\Controller\OurteamController;
use Ourteam\Service\OurteamManager;



/**
 * This is the factory for OurteamController. Its purpose is to instantiate the
 * controller.
 */
class OurteamControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $ourTeamManager = $container->get(OurteamManager::class);
        
        // Instantiate the controller and inject dependencies
        return new OurteamController($entityManager, $ourTeamManager);
    }
}
