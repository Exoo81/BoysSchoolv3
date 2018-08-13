<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\User;
use User\Form\UserForm;
use User\Form\PasswordChangeForm;
use User\Form\PasswordResetForm;

class UserController extends AbstractActionController{
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * User manager.
     * @var User\Service\UserManager 
     */
    private $userManager;
    
    
     /**
     * Constructor. 
     */
    public function __construct($entityManager,  $userManager){
        $this->entityManager = $entityManager; 
        $this->userManager = $userManager;
    }
    
    
    //action will display a web page containing the list of users 
    public function usersAction(){
        $title = 'Manage users';
              
        $users = $this->entityManager->getRepository(User::class)
                ->findBy([], ['id'=>'ASC']);
        
        return new ViewModel([
            'title' => $title,
            'users' => $users,
        ]);
    }
    

    //will display a page allowing the creation of a new user
    public function addAction(){
        $title = 'Add user';
        
        //create user form
        $form = new UserForm('create', $this->entityManager);
        
        // Get the list of all available roles (sorted by name).
        $allRoles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['name'=>'ASC']);
        
        $roleList = [];
        foreach ($allRoles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }
        
        $form->get('roles')->setValueOptions($roleList);
        
        // Check if user has submitted the form 
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Add user.
                $user = $this->userManager->addUser($data);
                
                // Redirect to "view" page
//                return $this->redirect()->toRoute('users', 
//                        ['action'=>'view', 'id'=>$user->getId()]); 
                return $this->redirect()->toRoute('user', 
                        ['action'=>'users']); 
            } 
            
        }
        
        //else just display form
        return new ViewModel([
            'title' => $title,
            'form' => $form
        ]);
    }


    //action will display a page for updating an existing user
    public function editAction(){
        
         
        $title = 'Edit user';
        
        //get id user from route
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        
        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
                ->find($id);
        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        //Access check
        if (!$this->access('user.edit') && 
            !$this->access('user.own.edit', ['user'=>$user])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        // Create user form
        $form = new UserForm('update', $this->entityManager, $user);
        
        // Get the list of all available roles (sorted by name).
        $allRoles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['name'=>'ASC']);
        $roleList = [];
        foreach ($allRoles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }
        
        $form->get('roles')->setValueOptions($roleList);
        
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Update the user.
                $this->userManager->updateUser($user, $data);

                // Redirect to "view" page
                    return $this->redirect()->toRoute('user', 
                            ['action'=>'view', 'id'=>$user->getId()]);
            }
            
        }else{
            
            $userRoleIds = [];
            foreach ($user->getRoles() as $role) {
                $userRoleIds[] = $role->getId();
            }
            
           $form->setData(array(
                    'title'=>$user->getTitle(),
                    'first_name'=>$user->getFirstName(),
                    'last_name'=>$user->getLastName(),
                    'email'=>$user->getEmail(),
                    'status'=>$user->getStatus(), 
                    'roles' => $userRoleIds
                ));
        }
        
        return new ViewModel([
            'title' => $title,
            'user' => $user,
            'form' => $form
        ]);
    }

    //will allow viewing an existing user 
    public function viewAction(){
        $title = 'User Details';
        
        //get id user from route
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
                ->find($id);
        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        return new ViewModel([
            'title' => $title,
            'user' => $user
        ]);
    }
    
    //action will give the admin the ability to change the password of an existing user
    public function changePasswordAction(){
        
        $title = 'Change password';
        $message = '';
        
        //get id user from route
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
                ->find($id);
        
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        //Access check
        if (!$this->access('user.own.changePassword', ['user'=>$user])) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        // Create "change password" form
        $form = new PasswordChangeForm('change');
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost(); 
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
//                    $this->flashMessenger()->addErrorMessage(
//                            'Sorry, the old password is incorrect. Could not set the new password.');
                    $message = 'The "Old Password" is incorrect';
                } else {
//                    $this->flashMessenger()->addSuccessMessage(
//                            'Changed the password successfully.');
                    
                    //Test
                    $message = 'pass changed';
                    
                    return $this->redirect()->toRoute('user', 
                        ['action'=>'view', 'id'=>$user->getId()]); 
                }
                
//                // Redirect to "view" page
//                return $this->redirect()->toRoute('user', 
//                        ['action'=>'view', 'id'=>$user->getId()]); 
            }
        }
        
        return new ViewModel([
            'title' => $title,
            'message' => $message,
            'user' => $user,
            'form' => $form
        ]);
        
    }
    
    //action will allow a user to reset his own password
    public function resetPasswordAction(){
        
        $title = 'Change password';
        $message = 'Enter your e-mail address below to reset your password';
        
        // Create form
        $form = new PasswordResetForm();
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
//            $message = 'aac';
            
            if($form->isValid()) {
//                $message = 'is valid';
                // Look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                        ->findOneByEmail($data['email']);
                if ($user!=null) {
//                    $message = 'user found';
                    // Generate a new password for user and send an E-mail 
                    // notification about that.
                    $this->userManager->generatePasswordResetToken($user);
                    
                    // Redirect to "message" page
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'sent']); 
                }else{
                    $message = 'User NOT found';
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'invalid-email']);
//                    $message = 'Enter your e-mail address below to reset your password';
                }
           }
        }
        
        return new ViewModel([
            'title' => $title,
            'message' => $message,
            'form' => $form
        ]);
        
    }
    
    /**
     * This action displays the "Reset Password" page. 
     */
    public function setPasswordAction(){
        
        $title = 'Set new password';
        $message = 'empty msg';
        
         $token = $this->params()->fromQuery('token', null);
         
         // Validate token length
        if ($token!=null && (!is_string($token) || strlen($token)!=32)) {
//            throw new \Exception('Invalid token type or length');
            $message = 'Invalid token type or length';
        }
        
        if($token===null || 
           !$this->userManager->validatePasswordResetToken($token)) {
//            return $this->redirect()->toRoute('users', 
//                    ['action'=>'message', 'id'=>'failed']);
            $message = 'Token is null or token not valid.';
        }
        
        // Create form
        $form = new PasswordChangeForm('reset');
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
//            $message = 'post';
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
//                $message = 'is valid';
                $data = $form->getData();
                
                // Set new password for the user.
                if ($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {
                    
                    $message = 'New password saved.';
//                    // Redirect to "message" page
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'set']); 
                }else{
                    $message = 'Token is null or token not valid or token not exist.';
//                 // Redirect to "message" page
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'failed']);
               }
            }
        }
        
        return new ViewModel([
            'title' => $title,
            'message' => $message,
            'form' => $form
        ]);
        
    }
}

