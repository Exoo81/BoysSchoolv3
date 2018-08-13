<?php

namespace Contact\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Contact\View\Helper\CurrentContact;
use Contact\Service\ContactManager;

class CurrentContactFactory{
    
    public function __invoke(ContainerInterface $container){   
        
        //$entityManager = $container->get('doctrine.entitymanager.orm_default');
        $contactManager = $container->get(ContactManager::class);
                        
        return new CurrentContact($contactManager);
    }
}

