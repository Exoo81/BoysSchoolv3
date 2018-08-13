<?php
namespace User\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Permission;
use User\Form\PermissionForm;
/**
 * This controller is responsible for permission management (adding, editing, 
 * viewing, deleting).
 */
class PermissionController extends AbstractActionController{
    
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Permission manager.
     * @var User\Service\PermissionManager 
     */
    private $permissionManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $permissionManager){
        $this->entityManager = $entityManager;
        $this->permissionManager = $permissionManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * list of permission.
     */
    public function indexAction() {
        
        $title = 'List of permissions';
        
        $permissions = $this->entityManager->getRepository(Permission::class)
                ->findBy([], ['name'=>'ASC']);
        
        return new ViewModel([
            'title' => $title,
            'permissions' => $permissions
        ]);
        
    } 
}

