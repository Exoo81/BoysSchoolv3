<?php

namespace Classes\Service;

use Zend\Permissions\Rbac\Rbac;
use User\Entity\User;
/**
 * This service is used for invoking user-defined RBAC dynamic assertions.
 */
class RbacAssertionManager{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Auth service.
     * @var Zend\Authentication\AuthenticationService 
     */
    private $authService;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $authService) {
        
        $this->entityManager = $entityManager;
        $this->authService = $authService;
        
    }
    
    public function assert(Rbac $rbac, $permission, $params){
        
        $currentUser = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->authService->getIdentity());

      
        if ($permission=='classes.own.edit' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        
        if ($permission=='post.own.add' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        
        if ($permission=='post.own.edit' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        
        if ($permission=='post.own.delete' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        
        return false;  
    }
}

