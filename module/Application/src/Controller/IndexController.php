<?php
/**
 *  INDEX  VIEW - application/index
 */

namespace Application\Controller;


use Application\Entity\WelcomeMsg;
use Application\Entity\OurAwards;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;




class IndexController extends AbstractActionController{
    

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * News manager.
     * @var Application\Service\NewsManager 
     */
    private $newsManager;
    
    /**
     * Welcome message manager.
     * @var Application\Service\WelcomeMsgManager 
     */
    private $welcomeMsgManager;
    
    /**
     * About us manager.
     * @var Application\Service\AboutUsManager 
     */
    private $aboutUsManager;
    
    /**
     * Newsletter manager.
     * @var Application\Service\NewsletterManager 
     */
    private $newsletterManager;
    
    
    /**
     * Subscription manager.
     * @var Application\Service\SubscriptionManager 
     */
    private $subscriptionManager;
    
    /**
     * Event manager.
     * @var Application\Service\EventManager 
     */
    private $eventManager;
    
    /**
     * Our Awards manager.
     * @var Application\Service\OurAwardsManager 
     */
    private $ourAwardsManager;
    
    /**
     * Season manager.
     * @var Season\Service\SeasonManager 
     */
    private $seasonManager;
    
    // Add this constructor:
    public function __construct($newsManager, $welcomeMsgManager, $aboutUsManager, $newsletterManager,
                        $subscriptionManager, $eventManager, $entityManager, $ourAwardsManager, $seasonManager){
        
        $this->entityManager = $entityManager;
        
        $this->newsManager = $newsManager; 
        $this->welcomeMsgManager = $welcomeMsgManager; 
        $this->aboutUsManager = $aboutUsManager;
        $this->newsletterManager = $newsletterManager;
        $this->subscriptionManager = $subscriptionManager;
        $this->eventManager = $eventManager;
        $this->ourAwardsManager = $ourAwardsManager;
        $this->seasonManager = $seasonManager;
        
    }
    
    
    public function indexAction(){
   
        //Get curren season news blog
        $newsBlog = $this->newsManager->getCurrentNewsBlog();
        // Get recent posts
        
        $schoolEvents = $this->eventManager->getSeasonEvents();
        
//        $currentEvents = $this->eventManager->getCurrentEvents();
        
        // Grab the paginator from the NewsTable:
        $paginator = $this->newsManager->fetchAll(true);
        $welcomeMsg = $this->entityManager->getRepository(WelcomeMsg::class)
                        ->find(1);
//        $aboutUs = $this->entityManager->getRepository(AboutUs::class)
//                        ->find(1);
//        $newsletters = $this->newsletterManager->getSeasonNewsletters();
        $newsletters = $this->newsletterManager->getSeasonNewslettersWrappedInMonths();
        
        $ourAwards = $this->entityManager->getRepository(OurAwards::class)
                ->findBy([], ['datePublished'=>'ASC']);
        
        $archiveSeasons = $this->seasonManager->getListOfCompletedSeasons();

        // Set the current page to what has been passed in query string,
        // Pagination
        $page = (int) $this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;
        $paginator->setCurrentPageNumber($page);


        return new ViewModel([
            'schoolEvents' => $schoolEvents,
            'paginator' => $paginator,
            'welcomeMsg' => $welcomeMsg,
//            'aboutUs' => $aboutUs,
            'newsletters' => $newsletters,
            'ourAwards' => $ourAwards,
            'blog' => $newsBlog,
            'archiveSeasons' => $archiveSeasons
             ]);
    }
    
    
    
    
    /********************************************************************/
    /********************************************************************/
    /********************************************************************/
    
    public function addnewsAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t add a news.';
        
        $formData = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('news.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'add news (1)';
            
            //save news
            $dataResponse = $this->newsManager->saveNews($formData);

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
   
    
    public function geteditnewsAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get news to edit.';
        
        //get post data
        $newsID = $this->params()->fromPost('id', 0);
        
        /*
         * check permmision
         */
        //Access check 
        if($this->access('news.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do edit news (1)';
            
            //get news to edit
            $dataResponse = $this->newsManager->getEditedNews($newsID);
            
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
    
    public function editnewsAction(){
        
        $dataResponse['success'] = false;
        $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit news.';
        
        //get POST data
        $dataToEdit = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('news.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do edit news (1)';
            
            //get news to edit
            $dataResponse = $this->newsManager->editNews($dataToEdit);
            
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
    
    public function deletenewsAction(){
        
        //get POST id
        $id =  $this->params()->fromPost('id', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('news.delete')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //get news to edit
            $dataResponse = $this->newsManager->deleteNews($id);
            
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

    

    public function addnewsletterAction(){
        
        $dataForm = $this->params()->fromPost();
         
          
        /*
         * check permmision
         */
        //Access check
        if($this->access('newsletter.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //add newsletter
            $dataResponse = $this->newsletterManager->addNewsletter($dataForm);

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
    
    public function deletenewsletterAction(){
        
        //get POST data
        $id =  $this->params()->fromPost('id', 0);
            
        /*
         * check permmision
         */
        //Access check
        if($this->access('newsletter.delete')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //delete newsletter
            $dataResponse = $this->newsletterManager->deleteNewsletter($id);
            
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
    
       
    
     public function addsubscriptionsAction(){
         
        //get POST email
        $email =  $this->params()->fromPost('email', '');
        
        //$dataResponse = $this->subscriptionManager->addSubscription($email);
        
        // 3. get request object
        $request = $this->getRequest();
        
        // 4. if request is HTTP (check if json)
        if ($request->isXmlHttpRequest()) { 
            
            // 6. put $data to $jasonData array
            $jsonData = $dataResponse; 

            // 7. create JsonModel with json data ($jsonData)
            $view = new JsonModel($jsonData); 
            // 8. set view as termianl
            $view->setTerminal(true);  
 
        } else { 
            $view = new ViewModel(); 
        } 
    
        return $view; 
         
     }
     
    public function geteditwelcomemsgAction(){
          
        // 1. take parametr from POST named 'message'
        $msgId = $this->params()->fromPost('msgId', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('welcomemsg.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //get welcome message to edit
            $dataResponse = $this->welcomeMsgManager->getEditWelcomeMsg($msgId);
            
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
    
    public function editwelcomemsgAction(){
          
        // get parametr from POST 
         $dataToEdit = $this->params()->fromPost();
         
         /*
         * check permmision
         */
        //Access check
        if($this->access('welcomemsg.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //edit welcome message
            $dataResponse = $this->welcomeMsgManager->editWelcomeMsg($dataToEdit);
            
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
    
    
    public function getaboutusAction(){
          
        // take parametr from POST
        $aboutusId=  $this->params()->fromPost('aboutUsId', 0);
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('aboutus.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //get "About us" info
            $dataResponse = $this->aboutUsManager->getEditAboutUs($aboutusId);
            
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
    
    public function editaboutusAction(){
          
        // get parametr from POST 
         $dataToEdit = $this->params()->fromPost();
         
        /*
         * check permmision
         */
        //Access check
        if($this->access('aboutus.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //edit "About us" info
            $dataResponse = $this->aboutUsManager->editAboutUs($dataToEdit);
            
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
    
    
    public function geteventsAction(){
        
        // take parametr from POST
        $current_date=  $this->params()->fromPost('date', '');
        
        $dataResponse = $this->eventManager->getCurrentEvents($current_date);
        
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
    
    public function getcalendarAction(){
        
        // take parametr from POST
        $year=  $this->params()->fromPost('year', '');
        $month=  $this->params()->fromPost('month', '');
        
        $dataResponse = $this->eventManager->getFullCalendarPage($year, $month+1);  //$month+1 ... data from array where start from 0.
        
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
    
    public function geteventAction(){
       
        
        // take parametr from POST
        $eventId=  $this->params()->fromPost('eventId', '');
        
        $dataResponse = $this->eventManager->getEvent($eventId);
        
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
    
    public function addeventAction(){
       
        $dataResponse['success'] = false;
        
        // get parametr from POST 
        $eventForm = $this->params()->fromPost();
        
        /*
         * check permmision
         */
        //Access check
        if($this->access('event.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            $dataResponse = $this->eventManager->saveEvent($eventForm);
            
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
    
    public function editeventAction(){

        // get parametr from POST 
         $editEventForm = $this->params()->fromPost();

         /*
         * check permmision
         */
        //Access check
        if($this->access('event.edit')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //edit event
            $dataResponse = $this->eventManager->editEvent($editEventForm);
            
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
    
    public function deleteeventAction(){
        
        $dataResponse['success'] = false;

        // get parametr from POST 
        $deleteEvent = $this->params()->fromPost();
        
        
         /*
         * check permmision
         */
        //Access check
        if($this->access('event.delete')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //delete event
            $dataResponse = $this->eventManager->deleteEvent($deleteEvent);
            
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
    
    public function addourawardAction(){
        
        $formData = $this->params()->fromPost();
        
        
         /*
         * check permmision
         */
        //Access check
        if($this->access('award.add')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //add award
            $dataResponse = $this->ourAwardsManager->addOurAward($formData);
            
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
    
    public function deleteourawardAction(){
        
        // get parametr from POST 
        $deleteAward = $this->params()->fromPost();
    
        /*
         * check permmision
         */
        //Access check
        if($this->access('award.delete')){
            
//            $dataResponse['success'] = false;                
//            $dataResponse['responseMsg'] = 'do delete news (1)';
            
            //delete award
            $dataResponse = $this->ourAwardsManager->deleteOurAward($deleteAward['id']);
            
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
