<?php
namespace Gallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class GalleryController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Subscription manager.
     * @var Gallery\Service\GalleryBlogManager 
     */
    private $galleryBlogManager;
    
    /**
     * Season manager.
     * @var Season\Service\SeasonManager 
     */
    private $seasonManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $galleryBlogManager, $seasonManager){
        $this->entityManager = $entityManager;
        $this->galleryBlogManager = $galleryBlogManager; 
        $this->seasonManager = $seasonManager;
    }
    
    public function indexAction(){
        $headTitle = "Gallery";
        
        //find blog 
        $galleryBlog = $this->galleryBlogManager->getCurrentGalleryBlog();
        if ($galleryBlog === null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $archiveSeasons = $this->seasonManager->getListOfCompletedSeasons();
        
        // Grab the paginator from the post for blog:
        $paginator = $this->galleryBlogManager->getGalleriesFromSeason(true);
        
        if($paginator != null){
            // Set the current page to what has been passed in query string,
            // Pagination
            $page = (int) $this->params()->fromQuery('page', 1);
            $page = ($page < 1) ? 1 : $page;
            $paginator->setCurrentPageNumber($page);
          
        }
        
        $colorList = $this->galleryBlogManager->getColorList();
        
        return new ViewModel([
            'blog' => $galleryBlog,
            'headTitle' => $headTitle,
            'galleryList' => $paginator,
            'colorList' => $colorList,
            'archiveSeasons' => $archiveSeasons
        ]);
        
    }

    /*
     * add gallery post
     */
    public function addgallerypostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t add a gallery.';
        
        $formData = $this->params()->fromPost();
        
        //check if any photo or video posted
        $containsGalleryElements = $this->galleryBlogManager->checkIfGallery();
        
        if(!$containsGalleryElements){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - The gallery should contain at least one photo or video.';
            
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
        if($this->access('gallery.add')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
//          $dataResponse['learningSupport'] = $learningSupport->getFullName();
                
            // save galleryPost
            $dataResponse = $this->galleryBlogManager->saveNewGalleryPost($formData);
                
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
    /*
     * get gallery to edit
     */
    public function geteditgalleryAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get gallery to edit.';

        $galleryID = $this->params()->fromPost('galleryId', 0);

        if ($galleryID<1) {
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR. Wrong gallery ID.';
            
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
        if($this->access('gallery.edit')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';
                
            $dataResponse = $this->galleryBlogManager->getGalleryToEdit($galleryID);
                
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
    
    public function editgallerypostAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit gallery.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        
        //Access check  
        if($this->access('gallery.edit')){
                
//          $dataResponse['success'] = false;                
//          $dataResponse['responseMsg'] = 'do (1)';

                
            //edit post
            $dataResponse = $this->galleryBlogManager->editGalleryPost($formData);
                
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


    
    public function deletegallerypostAction(){
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t delete gallery.';
        
        $galleryID = $this->params()->fromPost('galleryID', 0);
       
         /*
         * check permmision
         */
        //Access check  

            if($this->access('gallery.delete')){
                
//                $dataResponse['success'] = false;                
//                $dataResponse['responseMsg'] = 'do (1)';
                
                //delete gallery
                $dataResponse = $this->galleryBlogManager->deleteGalleryPost($galleryID);
                
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

