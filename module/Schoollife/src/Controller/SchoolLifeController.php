<?php
namespace Schoollife\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class SchoolLifeController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Schoollife\Service\SchoolLifeManager 
     */
    private $schoolLifeManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $schoolLifeManager){
        $this->entityManager = $entityManager;
        $this->schoolLifeManager = $schoolLifeManager; 
    }
    
    public function indexAction(){
        
        $headTitle = "School Life";
        
        $schoolLifeList = $this->schoolLifeManager->getActiveSchoolLife();

        return new ViewModel([
            'headTitle' => $headTitle,
            'schoolLifeList' => $schoolLifeList
        ]);
        
    }
    
    public function getschoollifeAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - School Life not found.';
        
        // get parametr from POST 
         $schoolLifeID =  $this->params()->fromPost('schoolLifeID', 0);
         
         //get policyIDschoolLifeID data
         $dataResponse = $this->schoolLifeManager->getSchoolLife($schoolLifeID);
        
        // get request object
        $request = $this->getRequest();
        
        // if request is HTTP (check if json)
        if ($request->isXmlHttpRequest()) { 
            
            $jsonData = $dataResponse; 
            $view = new JsonModel($jsonData); 
            $view->setTerminal(true);  
            
        } else { 
            $view = new ViewModel(); 
        }
        
        return $view;
    }
    
        public function addschoollifeAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - School Life not found.';
        
        $formData = $this->params()->fromPost();        
         /*
         * check permmision
         */
        //Access check
        if($this->access('schoolLife.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            // add School Life
            $dataResponse = $this->schoolLifeManager->saveSchoolLife($formData);
            
        }else{
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'User does not have permission (1)';
                
            // get request object
            $request = $this->getRequest();

            // if request is HTTP (check if json)
            if ($request->isXmlHttpRequest()) { 

                $jsonData = $dataResponse; 
                $view = new JsonModel($jsonData); 
                $view->setTerminal(true);  

            } else { 
                $view = new ViewModel(); 
            }

            return $view;
        }
        /*
         * end permision check
         */
        
        
        // get request object
        $request = $this->getRequest();
        
        // if request is HTTP (check if json)
        if ($request->isXmlHttpRequest()) { 
            
            $jsonData = $dataResponse; 
            $view = new JsonModel($jsonData); 
            $view->setTerminal(true);  
            
        } else { 
            $view = new ViewModel(); 
        }
        
        return $view;
    }
    
    public function editschoollifeAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - This "School Life" can not be edited.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('schoolLife.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            // add new policy
            $dataResponse = $this->schoolLifeManager->editSchoolLife($formData);
            
        }else{
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'User does not have permission (1)';
                
            // get request object
            $request = $this->getRequest();

            // if request is HTTP (check if json)
            if ($request->isXmlHttpRequest()) { 

                $jsonData = $dataResponse; 
                $view = new JsonModel($jsonData); 
                $view->setTerminal(true);  

            } else { 
                $view = new ViewModel(); 
            }

            return $view;
        }
        /*
         * end permision check
         */
        
        
        // get request object
        $request = $this->getRequest();
        
        // if request is HTTP (check if json)
        if ($request->isXmlHttpRequest()) { 
            
            $jsonData = $dataResponse; 
            $view = new JsonModel($jsonData); 
            $view->setTerminal(true);  
            
        } else { 
            $view = new ViewModel(); 
        }
        
        return $view;
    }
    
    public function deleteschoollifeAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - School Life not found.';
        
        // get parametr from POST 
         $schoolLifeID =  $this->params()->fromPost('schoolLifeID', 0);
         
         /*
         * check permmision
         */
        //Access check
        if($this->access('schoolLife.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            // delete School Life
            $dataResponse = $this->schoolLifeManager->deleteSchoolLife($schoolLifeID);
            
        }else{
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'User does not have permission (1)';
                
            // get request object
            $request = $this->getRequest();

            // if request is HTTP (check if json)
            if ($request->isXmlHttpRequest()) { 

                $jsonData = $dataResponse; 
                $view = new JsonModel($jsonData); 
                $view->setTerminal(true);  

            } else { 
                $view = new ViewModel(); 
            }

            return $view;
        }
        /*
         * end permision check
         */
        
        
        // get request object
        $request = $this->getRequest();
        
        // if request is HTTP (check if json)
        if ($request->isXmlHttpRequest()) { 
            
            $jsonData = $dataResponse; 
            $view = new JsonModel($jsonData); 
            $view->setTerminal(true);  
            
        } else { 
            $view = new ViewModel(); 
        }
        
        return $view;
    }

}


