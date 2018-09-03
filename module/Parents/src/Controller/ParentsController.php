<?php
namespace Parents\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use User\Entity\User;

class ParentsController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Parents\Service\ParentsManager 
     */
    private $parentsManager;
    
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $parentsManager){
        $this->entityManager = $entityManager;
        $this->parentsManager = $parentsManager; 
    }
    
    public function indexAction(){
        
        $headTitle = "Parents";
        
        $parentsInformation = $this->parentsManager->getAllParentsInformation();
        $parentsAssoc = $this->parentsManager->getParentsAssoc();
        $parentsAssocTeam = $this->parentsManager->getParentsAssocTeam();
        $booksList = $this->parentsManager->getBooksListSeason();
        //$policies = $this->parentsManager->getPolicies();
        
        return new ViewModel([
            'headTitle' => $headTitle,
            'parentsInformation' => $parentsInformation,
            'parentsAssoc' => $parentsAssoc,
            'parentsAssocTeam' => $parentsAssocTeam,
            'booksList' => $booksList,
            //'policies' => $policies
        ]);
        
    }

    public function addparentsinformationAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t add a parents information.';
        
        $formData = $this->params()->fromPost();
        
        //Access check
        if($this->access('parentsInformation.add')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
                
            // save parents information
            $dataResponse = $this->parentsManager->saveNewParentsInformation($formData);
                
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

    public function geteditparentsinformationAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get information to edit.';

        $parentsInformationID = $this->params()->fromPost('parentsInformationId', 0);

        if ($parentsInformationID<1) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Wrong information ID.';
            
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
         * check permmision
         */
        //Access check  
        if($this->access('parentsInformation.manage')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
                
            $dataResponse = $this->parentsManager->getParentsInformationToEdit($parentsInformationID);
                
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

    public function editparentsinformationAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit this information.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        
        //Access check  
        if($this->access('parentsInformation.manage')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';

                
            //edit post
            $dataResponse = $this->parentsManager->editParentsInformation($formData);
                
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

            }else{ 
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
            
        }else{ 
            $view = new ViewModel(); 
        }
        
        return $view;
        
    }
    
    public function deleteparentsinformationAction() {
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - The information can not be deleted.';
        
        $parentsInformationID = $this->params()->fromPost('parentsInformationID', 0);
    
        /*
         * check permmision
         */
        //Access check
        if($this->access('parentsInformation.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //delete parentsInformation
            $dataResponse = $this->parentsManager->deleteParentsInformation($parentsInformationID);
            
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
    
    
    public function addmembertoparentsassocAction() {
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - New member can not be added.';
        
        // get parametr from POST 
         $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('parentsAssoc.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //delete parentsInformation
            $dataResponse = $this->parentsManager->addNewMemberToParentsAssocTeam($formData);
            
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
    
    
    public function deleteparentsassocmemberAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member can not be deleted.';
        
        // get parametr from POST 
         $memberID =  $this->params()->fromPost('memberID', 0);
         
         /*
         * check permmision
         */
        //Access check
        if($this->access('parentsAssoc.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //delete member form parent association
            $dataResponse = $this->parentsManager->deleteParentsAssocTeamMember($memberID);
            
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
    
    public function activateparentsassocmemberAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Member can not be activated.';
        
        // get parametr from POST 
         $memberID =  $this->params()->fromPost('memberID', 0);
         
         /*
         * check permmision
         */
        //Access check
        if($this->access('parentsAssoc.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //delete member form parent association
            $dataResponse = $this->parentsManager->activateParentsAssocTeamMember($memberID);
            
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
    
    
    public function geteditparentsassocmeetingAction() {
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Parents Association can not be edit.';
        
        // get parametr from POST 
         $parentsAssocID =  $this->params()->fromPost('parentsAssocId', 0);
         
         
         /*
         * check permmision
         */
        //Access check
        if($this->access('parentsAssoc.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //edit meeting form parent association id
            $dataResponse = $this->parentsManager->getParentsAccocMeetingToEdit($parentsAssocID);
            
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
    
    public function editparentsassocmeetingAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Meeting can not be edit.';
        
        // get parametr from POST 
         $editMeeting = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('parentsAssoc.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete parents Information (1)';
            
            //edit meeting form parent association
            $dataResponse = $this->parentsManager->editParentsAssocMeeting($editMeeting);
            
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
    
    public function getbooklistAction() {
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Book list can not be found.';
        
        // get parametr from POST 
         $bookListID =  $this->params()->fromPost('bookListID', 0);
         
         //get bookList data
         $dataResponse = $this->parentsManager->getBookListInfo($bookListID);
        
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
    
    public function getaddbooklistselectAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Book list selection can not be received.';
        
        
         /*
         * check permmision
         */
        //Access check
        if($this->access('bookList.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do select option booklist (1)';
            
            //get select options for add booklist
            $dataResponse = $this->parentsManager->getSelectForAddBookList();
            
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


    public function addbooklistAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Book list can not be added.';
        
        // get data from POST 
         $formData = $this->params()->fromPost();
         
        /*
         * check permmision
         */
        //Access check
        if($this->access('bookList.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do add booklist (1)';
            
            //add bookList
            $dataResponse = $this->parentsManager->addBookList($formData);
            
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
    
    public function editbooklistAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Book list can not be edited.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        // get author id (teacher id) from JSON data
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        //get teacher id
        $teacherID = $formFieldsArray['authorID'];
        
        //Find teacher
//        $teacherID = 0;
        if ($teacherID<1) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Wrong teacher ID.';
            
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
        
        // Find a teacher with such ID.
        $teacher = $this->entityManager->getRepository(User::class)
                ->find($teacherID);
        
        
        if ($teacher == null) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Teacher not found.';
            
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
                
        //Access check  
        if($this->access('bookList.manage') ||
            $this->access('bookList.own.manage', ['user'=>$teacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
                
                //edit booklist
                $dataResponse = $this->parentsManager->editBookList($formData);
                
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
    
    
    public function deletebooklistAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Book list can not be deleted.';
        
        $bookListID = $this->params()->fromPost('bookListID', 0);
        $teacherID = $this->params()->fromPost('teacherID', 0);
        
         /*
         * check permmision
         */
        //Find teacher
//        $teacherID = 0;
        if ($teacherID<1) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Wrong teacher ID.';
            
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
        
        // Find a teacher with such ID.
        $teacher = $this->entityManager->getRepository(User::class)
                ->find($teacherID);
        
        
        if ($teacher == null) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Teacher not found.';
            
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
                
        //Access check  
        if($this->access('bookList.manage') ||
            $this->access('bookList.own.manage', ['user'=>$teacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
                
                //delete booklist
                $dataResponse = $this->parentsManager->deleteBookList($bookListID);
                
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
    
    public function getpolicyAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Policy not found.';
        
        // get parametr from POST 
         $policyID =  $this->params()->fromPost('policyID', 0);
         
         //get policyID data
         $dataResponse = $this->parentsManager->getPolicy($policyID);
        
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
    
    public function addpolicyAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Policy can not be added.';
        
        // get parametr from POST 
        $policyForm = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('policies.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            // add new policy
            $dataResponse = $this->parentsManager->savePolicy($policyForm);
            
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
    
    public function editpolicyAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Policy can not be edit.';
        
        
        // get parametr from POST 
        $policyForm = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('policies.manage')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do (1)';
            
            // edit policy
            $dataResponse = $this->parentsManager->editPolicy($policyForm);
            
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
    
    
    public function deletepolicyAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - Policy can not be deleted.';
  
        $policyID = $this->params()->fromPost('policyID', 0);
   
         /*
         * check permmision
         */
                
        //Access check  
        if($this->access('policies.manage')){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
                
                //delete policy
                $dataResponse = $this->parentsManager->deletePolicy($policyID);
                
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


