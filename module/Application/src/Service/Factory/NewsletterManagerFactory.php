<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\NewsletterManager;
use User\Service\SeasonManager;


/**
 * This is the factory class for NewsletterManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NewsletterManagerFactory{
    /**
     * This method creates the NewsletterManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $seasonManager = $container->get(SeasonManager::class);
                        
        return new NewsletterManager($entityManager, $seasonManager);
    }
}

