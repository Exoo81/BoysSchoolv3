<?php

namespace Application;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;



class Module implements ConfigProviderInterface{
    
    const VERSION = '3.0.3-dev';

    public function getConfig(){
        return include __DIR__ . '/../config/module.config.php';
    }
    
//for school events
    
    // The "init" method is called on application start-up and 
    // allows to register an event listener.
    public function init(ModuleManager $manager)
    {
        // Get event manager.
        $eventManager = $manager->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method. 
        $sharedEventManager->attach(__NAMESPACE__, 'dispatch', 
                                    [$this, 'onDispatch'], 100);
    }
    
    // Event listener method.
    public function onDispatch(MvcEvent $event)
    {
        // Get controller to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        // Get fully qualified class name of the controller.
        $controllerClass = get_class($controller);
        // Get module name of the controller.
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
           
        // Switch layout only for controllers belonging to our module.
        if ($moduleNamespace == __NAMESPACE__) {
            $viewModel = $event->getViewModel();
            $viewModel->setTemplate('layout/layout');
        } 
        
    }
    
    
// old news , welcomeMsg etc
//    public function getServiceConfig(){
//        return [
//            'factories' => [
//                
//                // News
//                Model\NewsTable::class => function($container) {
//                    $tableGateway = $container->get(Model\NewsTableGateway::class);
//                    return new Model\NewsTable($tableGateway);
//                },
//                Model\NewsTableGateway::class => function ($container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Model\News());
//                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
//                },
//                
//                // Welcome msg
//                Model\WelcomeMsgTable::class => function($container) {
//                    $tableGateway = $container->get(Model\WelcomeMsgTableGateway::class);
//                    return new Model\WelcomeMsgTable($tableGateway);
//                },
//                Model\WelcomeMsgTableGateway::class => function ($container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Model\WelcomeMsg());
//                    return new TableGateway('welcomeMsg', $dbAdapter, null, $resultSetPrototype);
//                },
//                
//                // About us
//                Model\AboutUsTable::class => function($container) {
//                    $tableGateway = $container->get(Model\AboutUsTableGateway::class);
//                    return new Model\AboutUsTable($tableGateway);
//                },
//                Model\AboutUsTableGateway::class => function ($container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Model\AboutUs());
//                    return new TableGateway('aboutUs', $dbAdapter, null, $resultSetPrototype);
//                },
//                
//                // Newsletter
//                Model\NewsletterTable::class => function($container) {
//                $tableGateway = $container->get(Model\NewsletterTableGateway::class);
//                return new Model\NewsletterTable($tableGateway);
//                },
//                Model\NewsletterTableGateway::class => function ($container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Model\Newsletter());
//                    return new TableGateway('newsletter', $dbAdapter, null, $resultSetPrototype);
//                },
//                  
//                // Subscribe
//                Model\SubscribeTable::class => function($container) {
//                $tableGateway = $container->get(Model\SubscribeTableGateway::class);
//                return new Model\SubscribeTable($tableGateway);
//                },
//                Model\SubscribeTableGateway::class => function ($container) {
//                    $dbAdapter = $container->get(AdapterInterface::class);
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Model\Subscribe());
//                    return new TableGateway('subscribe', $dbAdapter, null, $resultSetPrototype);
//                },
//                        
//            ],
//        ];
//    }
//    
//    public function getControllerConfig(){
//        return [
//            'factories' => [
//                Controller\IndexController::class => function($container) {
//                    return new Controller\IndexController(
//                        $container->get(Model\NewsTable::class),
//                        $container->get(Model\WelcomeMsgTable::class),
//                        $container->get(Model\AboutUsTable::class),
//                        $container->get(Model\NewsletterTable::class),
//                        $container->get(Model\SubscribeTable::class),
//                        // to let work old new line below added
//                            $container->get('doctrine.entitymanager.orm_default')
//                    );
//                },
//                
//                
//            ],
//        ];
//    }
}
