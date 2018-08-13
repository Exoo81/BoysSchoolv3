<?php

namespace Ourteam\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Ourteam\Entity\OurTeam;

/**
 * @ORM\Entity
 * @ORM\Table(name="management_team")
 */
class Management {
    // Board of Management member status constants.
    const STATUS_ACTIVE       = 1; // Active member.
    const STATUS_RETIRED      = 2; // Retired member.
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** 
     * @ORM\Column(name="management_role")  
     */
    protected $managementRole;
    
    /** 
     * @ORM\Column(name="title")  
     */
    protected $title;
    
    /** 
     * @ORM\Column(name="first_name")  
     */
    protected $firstName;
    
    /** 
     * @ORM\Column(name="last_name")  
     */
    protected $lastName;
    
    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    
    /**
     * Returns team member ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets team member ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns management Role.
     * @return string     
     */
    public function getManagementRole() {
        return $this->managementRole;
    } 
    
    /**
     * Returns Role string.
     * @return string     
     */
    public function getManagementRoleAsString() {
        $roleString = '';
        if($this->managementRole == 1){
            $roleString = 'Chairperson';
        }
        if($this->managementRole == 2){
            $roleString = 'Bishop\'s Representative';
        }
        if($this->managementRole == 3){
            $roleString = 'Parents\' Nominee';
        }
        if($this->managementRole == 4){
            $roleString = 'Teachers\' Representative';
        }
        if($this->managementRole == 5){
            $roleString = 'Community Representative';
        }
        if($this->managementRole == 6){
            $roleString = 'Secretary of the Board';
        }
        return $roleString;
    }
    
    /**
     * Sets management Role.
     * @param string $managementRole
     */
    public function setManagementRole($managementRole) {
        $this->managementRole = $managementRole;
    }
    
    
    /**
     * Returns title.
     * @return string     
     */
    public function getTitle() {
        return $this->title;
    } 
    
    /**
     * Returns title string.
     * @return string     
     */
    public function getTitleString() {
        $titleString = '';
        if($this->title == 1){
            $titleString = 'Mr.';
        }
        if($this->title == 2){
            $titleString = 'Mrs.';
        }
        if($this->title == 3){
            $titleString = 'Ms.';
        }
        return $titleString;
    }

    /**
     * Sets title.
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    
    /**
     * Returns first name.
     * @return string     
     */
    public function getFirstName() {
        return $this->firstName;
    }       

    /**
     * Sets first name.
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }
    
    /**
     * Returns last name.
     * @return string     
     */
    public function getLastName() {
        return $this->lastName;
    }       

    /**
     * Sets last name.
     * @param string $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    
    /**
     * Returns full name.
     * @return string     
     */
    public function getFullName() {
       $fullName = $this->getTitleString() .' '. $this->firstName .' '. $this->lastName;
        return $fullName;
    }       
    
    /**
     * Returns title + last name.
     * @return string     
     */
    public function getTitleLastName() {
       $titleLastName = $this->getTitleString() .' '. $this->lastName;
        return $titleLastName;
    }
    
    /**
     * Returns first name + last name.
     * @return string     
     */
    public function getFirstNameLastName() {
       $titleLastName = $this->firstName .' '. $this->lastName;
        return $titleLastName;
    }
    
    
    /**
     * Returns status.
     * @return int     
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList() {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }    
    
    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString(){
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];
        
        return 'Unknown';
    }    
    
    /**
     * Sets status.
     * @param int $status     
     */
    public function setStatus($status) {
        $this->status = $status;
    } 
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId()
        ];
    }
    
}

