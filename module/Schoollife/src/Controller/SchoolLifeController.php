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

}


