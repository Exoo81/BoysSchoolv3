<?php
namespace Schoollife\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Schoollife\Controller\SchoolLifeController;
use Schoollife\Service\SchoolLifeManager;


/**
 * This is the factory for SchoolLifeController. Its purpose is to instantiate the
 * controller.
 */
class SchoolLifeControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $schoolLifeManager = $container->get(SchoolLifeManager::class);
        
        // Instantiate the controller and inject dependencies
        return new SchoolLifeController($entityManager, $schoolLifeManager);
    }
}

