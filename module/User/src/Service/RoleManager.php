<?php
namespace User\Service;

use User\Entity\Role;
use User\Entity\Permission;

/**
 * This service is responsible for adding/editing roles.
 */
class RoleManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
//    /**
//     * RBAC manager.
//     * @var User\Service\RbacManager
//     */
//    //private $rbacManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager) { //add $rbacManager to constructor
        $this->entityManager = $entityManager;
        //$this->rbacManager = $rbacManager;
    }
    
    
    
}

