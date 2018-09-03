<?php
namespace Ourteam\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class OurteamController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Ourteam\Service\OurteamManager 
     */
    private $ourTeamManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $ourTeamManager){
        $this->entityManager = $entityManager;
        $this->ourTeamManager = $ourTeamManager; 
    }
    
    public function indexAction(){
        
        $headTitle = "Our Team";
        
        $principals = $this->ourTeamManager->getPrincipal();
        $teachers = $this->ourTeamManager->getTeachers();
        $management = $this->ourTeamManager->getBoardOfManagement();
        $learningSupport = $this->ourTeamManager->getLearningSupport();
        $sna = $this->ourTeamManager->getSNA();
        $asdUnit = $this->ourTeamManager->getAsdUnit();
        $secretary = $this->ourTeamManager->getSecretary();
        $caretaker = $this->ourTeamManager->getCaretaker();
     
        return new ViewModel([
            'headTitle' => $headTitle,
            'principalList' => $principals,
            'managementList' => $management,
            'teacherList' => $teachers,
            'learningSupportList' => $learningSupport,
            'snaList' => $sna,
            'asdUnitList' => $asdUnit,
            'secretaryList' => $secretary,
            'caretakerList' => $caretaker
        ]);
        
    }
    
    public function addourteammemberAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member (our team) can not be added.';
        
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('ourteam.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            //delete member of our team
            $dataResponse = $this->ourTeamManager->addOurTeamMamber($formData);
            
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
    
    public function addmanagementmemberAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member (Board of Management) can not be added.';
        
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('ourteam.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            //delete member of our team
            $dataResponse = $this->ourTeamManager->addBoardOfManagementMamber($formData);
            
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
    
    public function deleteourteamAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member (our team) can not be deleted.';
        
        $memberID = $this->params()->fromPost('memberID', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('ourteam.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            //delete member of our team
            $dataResponse = $this->ourTeamManager->deleteOurTeamMamber($memberID);
            
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
    
    public function activateourteamAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member (our team) can not be activate.';
        
        $memberID = $this->params()->fromPost('memberID', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('ourteam.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            //delete member of our team
            $dataResponse = $this->ourTeamManager->activateOurTeamMamber($memberID);
            
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
    
    public function deletemanagmentAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member (Board of Management) can not be deleted.';
        
        $memberID = $this->params()->fromPost('memberID', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('ourteam.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            //delete member of Board of Management
            $dataResponse = $this->ourTeamManager->deleteBoardOfManagementMamber($memberID);
            
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

