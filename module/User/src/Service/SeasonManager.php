<?php

namespace User\Service;

use User\Entity\User;
use User\Entity\Role;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

use User\Entity\Season;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class SeasonManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    
    
    /*
     * Get Season object with 'CURRENT' status
     */
    public function getCurrentSeason(){
        $status = 'CURRENT';
        $currentSeason = $this->entityManager->getRepository(Season::class)
                ->findOneByStatus($status);
        
        //$season_name = $currentSeason->getSeasonName();
        
        return $currentSeason;
    }
}

