<?php

namespace Ourteam\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a our_team entity.
 * @ORM\Entity()
 * @ORM\Table(name="our_team")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap( {"our_team" = "OurTeam", "principal" = "Ourteam\Entity\Principal", "teacher" = "Ourteam\Entity\Teacher", "learning_support" = "Ourteam\Entity\LearningSupport", "sna" = "Ourteam\Entity\SNA", "asd_unit" = "Ourteam\Entity\ASDUnit", "secretary" = "Ourteam\Entity\Secretary", "caretaker" = "Ourteam\Entity\Caretaker" , "parents_assoc" = "Parents\Entity\ParentsAssocTeam"} )
 */
class OurTeam {
    // Our Team member status constants.
    const STATUS_ACTIVE       = 1; // Active member.
    const STATUS_RETIRED      = 2; // Retired member.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
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
     * One OurTeam member has One User account.
     * @ORM\OneToOne(targetEntity="\User\Entity\User", inversedBy="ourTeamMember")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    
    
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
     * Returns full name (firstName + lastName).
     * @return string     
     */
    public function getFullNameShort() {
       $fullNameShort = $this->firstName .' '. $this->lastName;
        return $fullNameShort;
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
    
    
    /*
    * Returns associated user account.
    * @return \User\Entity\User
    */
    public function getUser() {
      return $this->user;
    }
    
    /**
     * Sets associated user account.
     * @param \User\Entity\User $user
     */
    public function setUser($user) {
      $this->user = $user;
      $user->setOurTeamMember($this);
    }
    
    
}

