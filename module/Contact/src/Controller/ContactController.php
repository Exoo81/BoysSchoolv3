<?php
namespace Contact\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ContactController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Contact\Service\ContactManager 
     */
    private $contactManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $contactManager){
        $this->entityManager = $entityManager;
        $this->contactManager = $contactManager; 
    }
    
    public function indexAction(){
        
        $headTitle = "Contact";

        return new ViewModel([
            'headTitle' => $headTitle,
        ]);
        
    }

    public function geteditcontactinfoAction(){
        
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get contact information to edit.';
        
        /*
         * check permmision
         */
        //Access check  
        if($this->access('contact.edit')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
            
            // get contact data to edit
            $dataResponse = $this->contactManager->getContactInformationToEdit();
                
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
    
    
    public function editcontactAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit contact.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check  
        if($this->access('contact.edit')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
            
            // edit contact info
            $dataResponse = $this->contactManager->editContactInformation($formData);
                
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
    
    
    public function sendmessageAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t send message.';
        
        $formData = $this->params()->fromPost();
        
        // sent message
        $dataResponse = $this->contactManager->sendMessage($formData);
        
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


