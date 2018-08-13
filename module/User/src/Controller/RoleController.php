<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\Permission;
use User\Form\RoleForm;
use User\Form\RolePermissionsForm;
/**
 * This controller is responsible for role management (adding, editing, 
 * viewing, deleting).
 */
class RoleController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Role manager.
     * @var User\Service\RoleManager 
     */
    private $roleManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $roleManager){
        $this->entityManager = $entityManager;
        $this->roleManager = $roleManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * list of roles.
     */
    public function indexAction(){
        
        $title = 'List of roles';
        
        
        $roles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['id'=>'ASC']);
        
        return new ViewModel([
            'title' => $title,
            'roles' => $roles
        ]);
    } 
    
    /**
     * This action displays a page allowing to add a new role.
     */
    public function addAction(){
        // not implemented
    }
}
