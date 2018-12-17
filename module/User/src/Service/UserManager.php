<?php

namespace User\Service;

use User\Entity\User;
use User\Entity\Role;
use Ourteam\Entity\OurTeam;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;


/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UserManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * Role manager.
     * @var User\Service\RoleManager
     */
    private $roleManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $roleManager) {
        $this->entityManager = $entityManager;
        $this->roleManager = $roleManager;
    }
      
    /**
     * This method adds a new user.
     */
    public function addUser($data, $memberID) {
        
        // Do not allow several users with the same email address.
        if($this->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }
        
        if ($memberID<1) {
            throw new \Exception("Member with id " . $memberID . " incorrect.");
        }
        
        //find member in DB
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($memberID);
        
        
        if($member == null){
            throw new \Exception("Member with id " . $memberID . " not found.");
        }
        
        // Create new User entity.
        $user = new User();
        $user->setEmail($data['email']);
//        $user->setTitle($data['title']);
//        $user->setFirstName($data['first_name']); 
//        $user->setLastName($data['last_name']);        
        // Encrypt password and store the password in encrypted state.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);        
        $user->setPassword($passwordHash);
        
        $user->setStatus($data['status']);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);  
        
        // Assign roles to user.
        $this->assignRoles($user, $data['roles']);
        
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($user);
        // Apply changes to database.
        $this->entityManager->flush();
        
        //merge user for member
        $member->setUser($user);   
        // Add the entity to the entity manager.
        $this->entityManager->persist($member);
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //TODO - not tested send email with password
        //$this->accountCreatedEmailToNewUser($user);
        
        return $user;
    }
    
    /**
     * This method updates data of an existing user (our team).
     */
    public function updateUser($user, $data) {
        // Do not allow to change user email if another user with such email already exits.
        if($user->getEmail()!=$data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }
        
        //find member in DB
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($user->getOurTeamMember()->getId());
        
        
        if($member == null){
            throw new \Exception("Member with id " . $user->getOurTeamMember()->getId() . " not found.");
        }
        
        $user->setEmail($data['email']);

        // Assign roles to user.
        $this->assignRoles($user, $data['roles']);        
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        $member->setTitle($data['title']);
        $member->setLastName($data['last_name']);

        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member);
        // Apply changes to database.
        $this->entityManager->flush();
        
        return true;
    }
    
    /**
     * This method updates data of an existing user (parents accoc).
     */
    public function updateUserParentAssoc($user, $data) {
        // Do not allow to change user email if another user with such email already exits.
        if($user->getEmail()!=$data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }
        
        //find member in DB
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($user->getOurTeamMember()->getId());
        
        
        if($member == null){
            throw new \Exception("Member with id " . $user->getOurTeamMember()->getId() . " not found.");
        }
        
        $user->setEmail($data['email']);

        // Assign roles to user.
        $this->assignRoles($user, $data['roles']);        
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        $member->setTitle($data['title']);
        $member->setLastName($data['last_name']);
        $member->setParentsAssocRole($data['parents_assoc_roles']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($member);
        // Apply changes to database.
        $this->entityManager->flush();
        
        return true;
    }
    
     /**
     * A helper method which assigns new roles to the user.
     */
    private function assignRoles($user, $roleIds){
        // Remove old user role(s).
        $user->getRoles()->clear();
        
        // Assign new role(s).
        foreach ($roleIds as $roleId) {
            $role = $this->entityManager->getRepository(Role::class)
                    ->find($roleId);
            if ($role==null) {
                throw new \Exception('Not found role by ID');
            }
            
            $user->addRole($role);
        }
    }
    
    /**
     * Checks whether an active user with given email address already exists in the database.     
     */
    public function checkUserExists($email) {
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($email);
        
        return $user !== null;
    }
    
    /**
     * This method is used to change the password for the given user. To change the password,
     * one must know the old password.
     */
    public function changePassword($user, $data)
    {
        $oldPassword = $data['old_password'];
        
        // Check that old password is correct
        if (!$this->validatePassword($user, $oldPassword)) {
            return false;
        }                
        
        $newPassword = $data['new_password'];
        
        // Check password length
        if (strlen($newPassword)<6 || strlen($newPassword)>64) {
            return false;
        }
        
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);
        $user->setPassword($passwordHash);
        
        // Apply changes
        $this->entityManager->flush();
        return true;
        
    }
    
    /**
     * Checks that the given password is correct.
     */
    public function validatePassword($user, $password) {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();
        
        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Generates a password reset token for the user. This token is then stored in database and 
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is 
     * directed to the Set Password page.
     */
    public function generatePasswordResetToken($user)
    {
        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        $user->setPasswordResetToken($token);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setPasswordResetTokenCreationDate($currentDate);  
        
        $this->entityManager->flush();
        
        $message = new Message();
        
        $title = 'Do you want to reset your password ?';
        $linkToReset = 'www.oranmoreboysns.ie/set-password?token='.$user->getResetPasswordToken();
        
        $message->addFrom("info@oranmoreboysns.ie", "RESET PASSWORD - Oranmore National Boys School Website")
                ->addTo($user->getEmail()) 
                ->setSubject($title);
        
        $content = '<html><body>';
            $content .= '<div style="background-color:#ecf0f1; border:1px solid #561818; width:max-content; padding:5px;">';
            $content .= '<font color="#561818" face="verdana" size="2" >';
                $content .= '<strong>INFO:</strong> If you want to change your password at www.oranmoreboysns.ie, click on the link below and enter your new password.';
            $content .= '</font>';
            $content .= '</div>';
            $content .= '<p>Click here -> '.$linkToReset.'</p>';
            $content .= '<div style="background-color:#ecf0f1; border:1px solid #561818; width:max-content; padding:5px;">';
            $content .= '<font color="#561818" face="verdana" size="2" >';
                $content .= '<strong>INFO:</strong>This link will be valid for 24 hours.';
                $content .= '<p>If you do not want to change the password, please ignore/delete this email.</p>';
            $content .= '</font>';
            $content .= '</div>';
        $content .= '</body></html>';
        
        $htmlPart = new \Zend\Mime\Part($content);
        $htmlPart->type = "text/html";
        
        $textPart = new \Zend\Mime\Part($linkToReset);
        $textPart->type = "text/plain";
        
        $body = new \Zend\Mime\Message();
        $body->setParts(array($textPart, $htmlPart));
        
        $message->setBody($body);
        
        $transport = new SendmailTransport();
        $transport->send($message);
        
    }
    
    /**
     * Checks whether the given password reset token is a valid one.
     */
    public function validatePasswordResetToken($passwordResetToken){
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByPasswordResetToken($passwordResetToken);
        
        if($user==null) {
            return false;
        }
        
        $tokenCreationDate = $user->getPasswordResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
        
        
    }
    
    /**
     * This method sets new password by password reset token.
     */
    public function setNewPasswordByToken($passwordResetToken, $newPassword)
    {
        if (!$this->validatePasswordResetToken($passwordResetToken)) {
           return false; 
        }
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByPasswordResetToken($passwordResetToken);
        
        if ($user==null) {
            return false;
        }
                
        // Set new password for user        
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($newPassword);        
        $user->setPassword($passwordHash);
                
        // Remove password reset token
        $user->setPasswordResetToken(null);
        $user->setPasswordResetTokenCreationDate(null);
        
        $this->entityManager->flush();
        
        return true;
    }
    
    /*
     * This methos sending emial to new created user with info about new account and link to changed password
     * 
     */
    
//    private function accountCreatedEmailToNewUser($user){
//        
//        // Generate a token.
//        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
//        $user->setPasswordResetToken($token);
//        
//        $currentDate = date('Y-m-d H:i:s');
//        $user->setPasswordResetTokenCreationDate($currentDate);  
//        
//        $this->entityManager->flush();
//        
//        $message = new Message();
//        
//        $title = 'New account on www.oranmoreboysns.ie';
//        $linkToReset = 'www.oranmoreboysns.ie/set-password?token='.$user->getResetPasswordToken();
//        
//        $message->addFrom("info@oranmoreboysns.ie", "NEW ACCOUNT - Oranmore National Boys School Website")
//                ->addTo($user->getEmail()) 
//                ->setSubject($title);
//        
//        $content = '<html><body>';
//            
//            $content .= '<p><strong>The account at www.oranmoreboysns.ie has been created for you.</strong></p>';
//            $content .= '<p><strong>To set your individual password click on the link --> '.$linkToReset.'</strong></p>';
//            $content .= '<div style="background-color:#ecf0f1; border:1px solid #561818; width:max-content; padding:5px;">';
//            $content .= '<font color="#561818" face="verdana" size="2" >';
//                $content .= '<strong>INFO:</strong>This link will be valid for 24 hours.';
//                $content .= '<p>If you do not want to change the password, please ignore/delete this email.</p>';
//            $content .= '</font>';
//            $content .= '</div>';
//            
//            $content .= '<p><strong>To log in to the website, click <a href="www.oranmoreboysns.ie/login">www.oranmoreboysns.ie/login</a></strong></p>';
//            $content .= '<p>Login: Your login is your email address</p>';
//            
//            $content .= '<p><strong>Please read the attachment with instructions on how to use your account at www.oranmoreboysns.ie</strong></p>';
//            
//            
//        $content .= '</body></html>';
//        
//        $htmlPart = new \Zend\Mime\Part($content);
//        $htmlPart->type = "text/html";
//        
//        $textPart = new \Zend\Mime\Part($linkToReset);
//        $textPart->type = "text/plain";
//        
//        $body = new \Zend\Mime\Message();
//        $body->setParts(array($textPart, $htmlPart));
//        
//        $message->setBody($body);
//        
//        $transport = new SendmailTransport();
//        $transport->send($message);
//        
//    }
    
}



