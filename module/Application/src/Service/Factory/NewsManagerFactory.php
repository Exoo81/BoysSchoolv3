<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\NewsManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for NewsManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NewsManagerFactory{
    /**
     * This method creates the UserManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new NewsManager($entityManager, $seasonManager);
    }
}

