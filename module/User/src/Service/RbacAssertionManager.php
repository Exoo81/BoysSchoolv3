<?php

namespace User\Service;

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

//        if ($permission=='user.own.view' && $params['user']->getId()==$currentUser->getId()){
//            return true;
//        }
//        
        if ($permission=='user.own.edit' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        if ($permission=='user.own.changePassword' && $params['user']->getId()==$currentUser->getId()){
            return true;
        }
        
        return false;  
    }
}

