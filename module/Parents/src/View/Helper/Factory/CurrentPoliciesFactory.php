<?php

namespace Parents\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Parents\View\Helper\CurrentPolicies;
use Parents\Service\ParentsManager;

class CurrentPoliciesFactory{
    
    public function __invoke(ContainerInterface $container){   
        
        //$entityManager = $container->get('doctrine.entitymanager.orm_default');
        $parentsManager = $container->get(ParentsManager::class);
                        
        return new CurrentPolicies($parentsManager);
    }
}

