<?php

namespace Schoollife\Service;


use Schoollife\Entity\SchoolLife;
use Classes\Entity\Post;
use User\Entity\User;
use Ourteam\Entity\Teacher;
use Ourteam\Entity\ASDUnit;
use Ourteam\Entity\LearningSupport;
use User\Entity\Season;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;



/**
 * This service is responsible for adding/editing etc. school life activit.
 * 
 */
class SchoolLifeManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    
    /**
     * Season manager.
     * @var User\Service\SeasonManager
     */
    private $seasonManager;
    
    private $currentSeason;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $seasonManager) {
        $this->entityManager = $entityManager;
        $this->seasonManager = $seasonManager;
        
        $this->currentSeason = $this->seasonManager->getCurrentSeason();
    }
    
    public function getActiveSchoolLife(){
        
        
        $schoolLifeList = $this->entityManager->getRepository(SchoolLife::class)
                     ->findByStatus(1);
        
        if($schoolLifeList != null){
            $schoolLifeListLeftRight = $this->splitSchoolLifeToLeftAndRight($schoolLifeList);
            
        }else{
            $schoolLifeList = null;
        }
        
        return $schoolLifeListLeftRight;
    }
    
    private function splitSchoolLifeToLeftAndRight($schoolLifeList){
        
        $schoolLifeListLeftRight = array();
        $schoolLifeRightList = array();
        $schoolLifeLeftList = array();
        
        $moduloCounter = 0;
        foreach($schoolLifeList as $schoolLife){
            if($moduloCounter % 2){
                array_push($schoolLifeLeftList, $schoolLife);
                
            }else{
                array_push($schoolLifeRightList, $schoolLife);
            }
            
            $moduloCounter++;
        }
        
        $schoolLifeListLeftRight['right'] = $schoolLifeRightList;
        $schoolLifeListLeftRight['left'] = $schoolLifeLeftList;
        
        return $schoolLifeListLeftRight;
        
    }
    
}

