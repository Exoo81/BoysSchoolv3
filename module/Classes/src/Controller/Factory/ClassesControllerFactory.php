<?php
namespace Classes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Classes\Controller\ClassesController;
use Classes\Service\ClassesManager;


/**
 * This is the factory for ClassesController. Its purpose is to instantiate the
 * controller.
 */
class ClassesControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $classesManager = $container->get(ClassesManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ClassesController($entityManager, $classesManager);
    }
}

