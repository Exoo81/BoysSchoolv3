<?php

namespace User;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;
use User\Service\AuthManager;

use User\Service\SeasonManager;
use Application\Service\AboutUsManager;

use User\Entity\Season;

class Module implements ConfigProviderInterface{
    
    
    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig(){
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners. 
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method. 
        $sharedEventManager->attach(AbstractActionController::class, 
                MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
        
        /*
         *  Add var currentSeason to layout (for all pages)
         */
        $seasonManager = $event->getApplication()->getServiceManager()->get(SeasonManager::class); 
        //get Object CurrentSeason
        $currentSeason = $seasonManager->getCurrentSeason();
//        $currentSeasonActiveted = $this->chceckIfCurentSeasonIsActive($currentSeason);
        
        // get viewModel layout
        $viewModel = $event->getViewModel();
        $viewModel->setVariable('currenSeason', $currentSeason);
//        $viewModel->setVariable('seasonIsActive', $currentSeasonActiveted);
        
        
        /*
         * Add vsr aboutUs to layout (for all pages)
         */
        $aboutUsManager = $event->getApplication()->getServiceManager()->get(AboutUsManager::class);
        //get Object CurrentSeason
        $aboutUs = $aboutUsManager->getCurrentAboutUs();
        
        // get viewModel layout
        $viewModel->setVariable('aboutUs', $aboutUs);
    }
    
 /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user 
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);
        
        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        
        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
        
        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName!=AuthController::class)
        {
            $result = $authManager->filterAccess($controllerName, $actionName);
            
            if ($result==AuthManager::AUTH_REQUIRED) {
                // Remember the URL of the page the user tried to access. We will
                // redirect the user to that URL after successful login.
                $uri = $event->getApplication()->getRequest()->getUri();
                // Make the URL relative (remove scheme, user info, host name and port)
                // to avoid redirecting to other domain by a malicious user.
                $uri->setScheme(null)
                    ->setHost(null)
                    ->setPort(null)
                    ->setUserInfo(null);
                $redirectUrl = $uri->toString();
                // Redirect the user to the "Login" page.
                return $controller->redirect()->toRoute('login', [], 
                        ['query'=>['redirectUrl'=>$redirectUrl]]);
            }
            else if ($result==AuthManager::ACCESS_DENIED) {
                // Redirect the user to the "Not Authorized" page.
                return $controller->redirect()->toRoute('not-authorized');
            }
        }
        
    }
    
//    private function chceckIfCurentSeasonIsActive(Season $currentSeason){
//        
//        $endYear = $currentSeason->getEndYear();
//        $currentYear = date('Y');
//        $currentMonth = date('m');
//        
//        if($endYear > $currentYear){
//            return 1;     
//        }
//        if($endYear == $currentYear){
//            //chceck if month bigger then 7
//            if($currentMonth > 7){
//                return 0;
//            }else{
//                return 1;
//            }
//        }
//        if($endYear < $currentYear){
//            return 0;     
//        }
//    }
}
