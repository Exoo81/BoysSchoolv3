<?php
namespace User\Controller\Plugin;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use User\Entity\User;
/**
 * This controller plugin is designed to let you get the currently logged in User entity
 * inside your controller.
 */
class CurrentUserPlugin extends AbstractPlugin{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Authentication service.
     * @var Zend\Authentication\AuthenticationService 
     */
    private $authService;
    
    /**
     * Logged in user.
     * @var User\Entity\User
     */
    private $user = null;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $authService) {
        $this->entityManager = $entityManager;
        $this->authService = $authService;
    }
}

