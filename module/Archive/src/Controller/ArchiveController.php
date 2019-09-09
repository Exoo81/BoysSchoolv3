<?php

/* 
 * Archive Controller
 */

namespace Archive\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ArchiveController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Archive manager.
     * @var Archive\Service\ArchiveManager 
     */
    private $archiveManager;
    
    /**
     * Season manager.
     * @var User\Service\SeasonManager 
     */
    private $seasonManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $archiveManager, $seasonManager){
        $this->entityManager = $entityManager;
        $this->archiveManager = $archiveManager; 
        $this->seasonManager = $seasonManager;
    }
    
    public function indexAction(){
        
        $headTitle = "News archive";
        
        //get id user from route
        $seasonID = (int)$this->params()->fromRoute('id', -1);
        if ($seasonID<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $season = $this->seasonManager->getArchiveSeason($seasonID);
        
        if ($season === null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Grab the paginator from the NewsTable:
        $paginator = $this->archiveManager->getAllPostFromCompletedSeason(true, $season);
        
        if($paginator != null){
            // Set the current page to what has been passed in query string,
            // Pagination
            $page = (int) $this->params()->fromQuery('page', 1);
            $page = ($page < 1) ? 1 : $page;
            $paginator->setCurrentPageNumber($page);
        }
        
        return new ViewModel([
            'headTitle' => $headTitle,
            'season' => $season,
            'paginator' => $paginator
        ]);
        
    }

    public function addAction(){
        
    }

    public function editAction(){
        
    }

    public function deleteAction(){
        
    }
}


