<?php

namespace Classes\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Classes\Controller\ClassBlogController;
use Classes\Service\PostManager;

/**
 * This is the factory for ClassBlogController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ClassBlogControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ClassBlogController($entityManager, $postManager);
    }
}

