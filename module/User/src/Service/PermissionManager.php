<?php

namespace User\Service;

use User\Entity\Permission;


/**
 * This service is responsible for adding/editing permissions.
 */
class PermissionManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * RBAC manager.
     * @var User\Service\RbacManager
     */
//    private $rbacManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager) { //add $rbacManager to constructor
        $this->entityManager = $entityManager;
//        $this->rbacManager = $rbacManager;
    }
}

