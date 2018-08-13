<?php
namespace Contact\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Contact\Controller\ContactController;
use Contact\Service\ContactManager;



/**
 * This is the factory for ContactController. Its purpose is to instantiate the
 * controller.
 */
class ContactControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $contactManager = $container->get(ContactManager::class);
        
        // Instantiate the controller and inject dependencies
        return new ContactController($entityManager, $contactManager);
    }
}

