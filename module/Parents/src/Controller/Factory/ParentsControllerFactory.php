<?php
namespace Parents\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Parents\Controller\ParentsController;
use Parents\Service\ParentsManager;
use User\Service\SeasonManager;


/**
 * This is the factory for ParentsController. Its purpose is to instantiate the
 * controller.
 */
class ParentsControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $parentsManager = $container->get(ParentsManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ParentsController($entityManager, $parentsManager);
    }
}

