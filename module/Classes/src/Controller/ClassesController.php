<?php

namespace Classes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Classes\Entity\Blog;
use User\Entity\User;

use Zend\View\Model\JsonModel;

class ClassesController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Classes\Service\ClassesManager 
     */
    private $classesManager;
    
    
     /**
     * Constructor. 
     */
    public function __construct($entityManager, $classesManager){
        $this->entityManager = $entityManager;
        $this->classesManager = $classesManager; 
    }
    
    public function indexAction(){
        
        
        //$current_season = $this->seasonManager->getCurrentSeason();       
        $title = "Classes";
        
        $blogList =  $this->classesManager->getBlogsFromSeason();
        
        return new ViewModel([
            'title' => $title,
            'blogList' => $blogList,
            
        ]);
        
    }
    
    public function getselectoptionsAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'An error occurred while generating the form';
 
        /*
         * check permmision
         */
        //Access check
        if($this->access('classes.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'add news (1)';
            
            //get selection for form
            $dataResponse = $this->classesManager->getSelectionForm();

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

            }else { 
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

    public function addclassblogAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t create a new blog';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('classes.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'add news (1)';
            
            //save new class blog
            $dataResponse = $this->classesManager->saveNewBlog($formData);

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

            }else { 
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
    
    public function geteditclassblogAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - The blog data can not be received.';
  
        /*
         * check permmision
         */
        
        //get teacher id
        $teacherID = $this->params()->fromPost('teacherID', 0);
        //get learning support id
        $learningSupportID = $this->params()->fromPost('learningSupportID', 0);
        
        
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
        
        // Find a user with such ID.
        $teacher = $this->entityManager->getRepository(User::class)
                ->find($teacherID);
        
        $learningSupport = null;
        if($learningSupportID !== null){
            $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
        
        
        
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
        if($learningSupport !== null){
            if($this->access('classes.edit') ||
                    $this->access('classes.own.edit', ['user'=>$teacher]) ||
                        $this->access('classes.own.edit', ['user'=>$learningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
                //get data form edit class blog form
                $blogID = $this->params()->fromPost('classBlogID', 0);
                $dataResponse = $this->classesManager->getClassBlogEditData($blogID);
                
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
            
            
        }else{
            if($this->access('classes.edit') ||
                    $this->access('classes.own.edit', ['user'=>$teacher])){
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['learningSupport'] = $learningSupport;
                
                //get data form edit class blog form
                $blogID = $this->params()->fromPost('classBlogID', 0);
                $dataResponse = $this->classesManager->getClassBlogEditData($blogID);
                
                
            }else{
                //return error
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'User does not have permission (2)';
                
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

    public function editclassblogAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - The blog can not be edited.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * Check permmision
         */
        // get teacher id and learning support id from JSON data
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        //get teacher id
        $teacherID = $formFieldsArray['editClassTeacherID'];
        //get learning support id
        $learningSupportID = $formFieldsArray['editClassLearningSupportID'];
        //get blog id
        $blogID = $formFieldsArray['editClassBlogID'];
        
        
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
        
        //Find learning support if id not null
        $learningSupport = null;
        if($learningSupportID !== null){
            $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
        
        /*
         * Find blog
         */
        if($blogID<1){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Wrong blog ID.';
            
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
        
        $blog = $this->entityManager->getRepository(Blog::class)
                ->find($blogID);
    
        if($blog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Blog not found.';
            
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
        //get old teacher
        $oldTeacher = $blog->getTeacher();
        //get old learning support
        $oldLearningSupport = $blog->getLearningSupport();

        
        //Access check (base on old $blog data)
        if($oldLearningSupport !== null){
            if($this->access('classes.edit') ||
                    $this->access('classes.own.edit', ['user'=>$oldTeacher]) ||
                        $this->access('classes.own.edit', ['user'=>$oldLearningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['oldLearningSupport'] = $oldLearningSupport->getFullName();
                
                //edit blog
                $dataResponse = $this->classesManager->editClassBlog($formData);
                
            }else{
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
            
        }else{
            if($this->access('classes.edit') ||
                    $this->access('classes.own.edit', ['user'=>$oldTeacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['oldLearningSupport'] = $oldLearningSupport;
                
                //edit blog
                $dataResponse = $this->classesManager->editClassBlog($formData);
            }else{
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'User does not have permission (2)';
                
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

    public function deleteblogAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - The blog can not be deleted.';
        
        $blogID = $this->params()->fromPost('blogID', 0);
        
        

        
        /*
         * check permmision
         */
        //Access check
        if($this->access('classes.delete')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete class blog (1)';
            
            //delete blog
            $dataResponse = $this->classesManager->deleteClassBlog($blogID);
            
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

