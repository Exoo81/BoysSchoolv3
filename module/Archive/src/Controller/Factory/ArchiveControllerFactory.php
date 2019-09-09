<?php
namespace Archive\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Archive\Controller\ArchiveController;
use Archive\Service\ArchiveManager;
use User\Service\SeasonManager;


/**
 * This is the factory for ArchiveController. Its purpose is to instantiate the
 * controller.
 */
class ArchiveControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $archiveManager = $container->get(ArchiveManager::class);
        $seasonManager = $container->get(SeasonManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ArchiveController($entityManager, $archiveManager, $seasonManager);
    }
}

