<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\UserManager;
use User\Service\RoleManager;
use User\View\Helper\CurrentUser;
//use User\Service\PermissionManager;

/**
 * This is the factory class for UserManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class UserManagerFactory{
    /**
     * This method creates the UserManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null){  
        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $roleManager = $container->get(RoleManager::class);

                        
        return new UserManager($entityManager, $roleManager);
    }
}