<?php

namespace Classes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Classes\Entity\Blog;
use Classes\Entity\ClassBlog;
use User\Entity\User;

use Zend\View\Model\JsonModel;


class ClassBlogController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    
    /**
     * Subscription manager.
     * @var Classes\Service\PostManager 
     */
    private $postManager;
    
     /**
     * Constructor. 
     */
    public function __construct($entityManager, $postManager){
        $this->entityManager = $entityManager;
        $this->postManager = $postManager;
    }
    
    public function indexAction() {

        //get id blog from route
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        //find blog 
        $blog = $this->entityManager->getRepository(ClassBlog::class)
                ->find($id);
        if ($blog === null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
    
        // Grab the paginator from the post for blog:
        $paginator = $this->postManager->getAllPostsForBlog($blog->getId(), true);
        
        if($paginator != null){
            // Set the current page to what has been passed in query string,
            // Pagination
            $page = (int) $this->params()->fromQuery('page', 1);
            $page = ($page < 1) ? 1 : $page;
            $paginator->setCurrentPageNumber($page);
        }
        
        $color = $this->params()->fromRoute('color', "");  
        
        
        $headTitle = $blog->getTeacher()->getOurTeamMember()->getTitleLastName().' '.$blog->getLevelAsString().' BLOG ('.$blog->getSeason()->getSeasonName().')';
         
        return new ViewModel([
            'headTitle' => $headTitle,
            'blog' => $blog,
            'postsList' => $paginator,
            'color' => $color,
        ]);
    }
    
    
    public function addpostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t add a new post.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        
        // get teacher id and learning support id from JSON data
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        //get teacher id
        $teacherID = $formFieldsArray['teacherID'];
        //get learning support id
        $learningSupportID = $formFieldsArray['learningSupportID'];
        
        // check teacherID
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
        
        //Find learning support if not null
        $learningSupport = null;
        if($learningSupportID !== null){
            $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
        

        //Access check
        if($learningSupport !== null){
            if($this->access('post.add') ||
                    $this->access('post.own.add', ['user'=>$teacher]) ||
                        $this->access('post.own.add', ['user'=>$learningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
                // save post
                $dataResponse = $this->postManager->saveNewPost($formData);
                
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
            if($this->access('post.add') ||
                    $this->access('post.own.add', ['user'=>$teacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['learningSupport'] = $learningSupport;
                
                // save post
                $dataResponse = $this->postManager->saveNewPost($formData);
                
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
    
    public function geteditpostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get post to edit.';

        $postID = $this->params()->fromPost('postId', 0);
        $learningSupportID = $this->params()->fromPost('learningSupportID', 0);
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
        
        // Find learning support if exist
        if($learningSupportID !== null){
        $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
           
        //Access check  
        if($learningSupport !== null){
            if($this->access('post.edit') ||
                    $this->access('post.own.edit', ['user'=>$teacher]) ||
                        $this->access('post.own.edit', ['user'=>$learningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
                $dataResponse = $this->postManager->getEditedPost($postID);
                
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
            if($this->access('post.edit') ||
                    $this->access('post.own.edit', ['user'=>$teacher])){
            
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['learningSupport'] = $learningSupport;
                
                $dataResponse = $this->postManager->getEditedPost($postID);
                
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
    
public function editpostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit post.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        // get teacher id and learning support id from JSON data
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        //get teacher id
        $teacherID = $formFieldsArray['editPostTeacherID'];
        //get learning support id
        $learningSupportID = $formFieldsArray['editPostLearningSupportID'];
        
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
        
        //Find learning support if id not null
        $learningSupport = null;
        if($learningSupportID !== null){
            $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
        
        
        //Access check  
        if($learningSupport !== null){
            if($this->access('post.edit') ||
                    $this->access('post.own.edit', ['user'=>$teacher]) ||
                        $this->access('post.own.edit', ['user'=>$learningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
                //edit post
                $dataResponse = $this->postManager->editPost($formData);
                
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
            if($this->access('post.edit') ||
                    $this->access('post.own.edit', ['user'=>$teacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['learningSupport'] = $learningSupport;
                
                //edit post
                $dataResponse = $this->postManager->editPost($formData);
                
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
    
    public function deletepostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t delete post.';
        
        $postID = $this->params()->fromPost('postID', 0);
        $teacherID = $this->params()->fromPost('teacherID', 0);
        $learningSupportID = $this->params()->fromPost('learningSupportID', 0);

        
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
        
        //Find learning support if id not null
        $learningSupport = null;
        if($learningSupportID !== null){
            $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($learningSupportID);
        }
                
        //Access check  
        if($learningSupport !== null){
            if($this->access('post.delete') ||
                    $this->access('post.own.delete', ['user'=>$teacher]) ||
                        $this->access('post.own.delete', ['user'=>$learningSupport])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
//                $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
                //delete post
                $dataResponse = $this->postManager->deletePost($postID);
                
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
            if($this->access('post.edit') ||
                $this->access('post.own.edit', ['user'=>$teacher])){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (2)';
//                $dataResponse['learningSupport'] = $learningSupport;
            
                //delete post
                $dataResponse = $this->postManager->deletePost($postID);
            
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
    
}

