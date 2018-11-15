<?php
namespace Schoollife\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
        //$current_season = $this->seasonManager->getCurrentSeason();       
        $title = "School Life";
        
        $schoolLifeList =  $this->schoolLifeManager->getActiveSchoolLife();
        
        
        return new ViewModel([
            'title' => $title,
            'schoolLifeList' => $schoolLifeList,
            
        ]);
        
    }

    public function addAction(){
        
    }

    public function editAction(){
        
    }

    public function deleteAction(){
        
    }
}


