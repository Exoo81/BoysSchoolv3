<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Entity\Role;
use User\Entity\User;
use Ourteam\Entity\OurTeam;
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
    public function addAccountAction(){
        
        $title = 'Create account';
        
        //get id member from route
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id<1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find a member (our team) with such ID.
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($id);
        
        if ($member == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        if($member->getUser() !== null){
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        //Access check
        if (!$this->access('user.add')) {
            return $this->redirect()->toRoute('not-authorized');
        }
        
        //create user form
        $form = new UserForm('create', $this->entityManager);
        
        $form->get('title')->setValue($member->getTitle());
        $form->get('first_name')->setValue($member->getFirstName());
        $form->get('last_name')->setValue($member->getLastName());
        $form->get('status')->setValue($member->getStatus());
        
        // Get the list of all available roles (sorted by name).
        $allRoles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['id'=>'ASC']);
        
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
                $user = $this->userManager->addUser($data, $member->getId());
                
                // Redirect to "view" page
                return $this->redirect()->toRoute('user', 
                        ['action'=>'view', 'id'=>$user->getId()]); 
//                return $this->redirect()->toRoute('user', 
//                        ['action'=>'users']); 
            } 
            
        }
        
        //else just display form
        return new ViewModel([
            'title' => $title,
            'member' => $member,
            'form' => $form
        ]);
        
    }


    //action will display a page for updating an existing user (member Our Team)
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
                ->findBy([], ['id'=>'ASC']);
        $roleList = [];
        foreach ($allRoles as $role) {
            $roleList[$role->getId()] = $role->getName();
        }
        
        $form->get('roles')->setValueOptions($roleList);
        
        //show extra field if parents assoc member (parents_assoc_role field)
        
        
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
                    'title'=>$user->getOurTeamMember()->getTitle(),
                    'first_name'=>$user->getOurTeamMember()->getFirstName(),
                    'last_name'=>$user->getOurTeamMember()->getLastName(),
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
    
    //action will display a page for updating an existing user (member Parents Assoc)
    public function editparentsassocAction(){
        
         
        $title = 'Edit Parents association user';
        
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
        $form = new UserForm('update_parents_assoc', $this->entityManager, $user);
        
        // Get the list of all available roles (sorted by name).
        $allRoles = $this->entityManager->getRepository(Role::class)
                ->findBy([], ['id'=>'ASC']);
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
                $this->userManager->updateUserParentAssoc($user, $data);

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
                    'title'=>$user->getOurTeamMember()->getTitle(),
                    'first_name'=>$user->getOurTeamMember()->getFirstName(),
                    'last_name'=>$user->getOurTeamMember()->getLastName(),
                    'email'=>$user->getEmail(),
                    'status'=>$user->getStatus(), 
                    'roles' => $userRoleIds,
                    'parents_assoc_roles'=>$user->getOurTeamMember()->getParentsAssocRole(),
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
        
        $title = 'Reset password';
        $message = 'Enter your e-mail address below to reset your password';
        $message_error = '';
        
        // Create form
        $form = new PasswordResetForm();
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            
            $form->setData($data);
            
            
            if($form->isValid()) {
//                $message = 'is valid';
                // Look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                        ->findOneByEmail($data['email']);
                if ($user!=null) {
                    $message_error = '';
                    $message = 'A link to reset your password has been sent to the email you entered.';
                    // Generate a new password for user and send an E-mail 
                    // notification about that.
                    $this->userManager->generatePasswordResetToken($user);
                    
                    $form = new PasswordResetForm();
                    
                    // Redirect to "message" page
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'sent']); 
                }else{
                    $message = '';
                    $message_error = 'The e-mail you entered does not exist.';
//                    return $this->redirect()->toRoute('users', 
//                            ['action'=>'message', 'id'=>'invalid-email']);
//                    $message = 'Enter your e-mail address below to reset your password';
                }
           }else{
                $message = '';
                $message_error = 'Incorrect code. Try again.';
           }
        }
        
        return new ViewModel([
            'title' => $title,
            'message' => $message,
            'message_error' => $message_error,
            'form' => $form
        ]);
        
    }
    
    /**
     * This action displays the "Reset Password" page. 
     */
    public function setPasswordAction(){
        
        $title = 'Set new password';
        $message = '';
        
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
                    
                    $message = 'New password saved. Do you want to log in now ? <a href="/login">Click to Login</a>?';
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

