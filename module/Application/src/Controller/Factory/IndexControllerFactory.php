<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\IndexController;
use Application\Service\NewsManager;
use Application\Service\WelcomeMsgManager;
use Application\Service\AboutUsManager;
use Application\Service\NewsletterManager;
use Application\Service\SubscriptionManager;
use Application\Service\SchoolEventManager;
use Application\Service\OurAwardsManager;
use User\Service\SeasonManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller.
 */
class IndexControllerFactory implements FactoryInterface{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $newsManager = $container->get(NewsManager::class);
        $welcomeMsgManager = $container->get(WelcomeMsgManager::class);
        $aboutUsManager = $container->get(AboutUsManager::class);
        $newsletterManager = $container->get(NewsletterManager::class);
        $subscriptionManager = $container->get(SubscriptionManager::class);
        $eventManager = $container->get(SchoolEventManager::class);
        $ourAwardsManager = $container->get(OurAwardsManager::class);
        $seasonManager = $container->get(SeasonManager::class);
        
        // Instantiate the controller and inject dependencies
        return new IndexController($newsManager, $welcomeMsgManager, $aboutUsManager, $newsletterManager,
                                        $subscriptionManager, $eventManager, $entityManager, $ourAwardsManager,
                                        $seasonManager);
    }
}

