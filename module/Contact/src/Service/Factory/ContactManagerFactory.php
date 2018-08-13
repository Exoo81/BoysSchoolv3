<?php
namespace Contact\Service\Factory;

use Interop\Container\ContainerInterface;
use Contact\Service\ContactManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for ContactManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ContactManagerFactory{
    /**
     * This method creates the ParentsManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new ContactManager($entityManager, $seasonManager);
    }
}

