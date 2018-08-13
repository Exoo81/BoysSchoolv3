<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parents_association_team")
 */
class ParentsAssocTeam {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
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
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
    
    /** 
     * @ORM\Column(name="role")  
     */
    protected $role;
    
    /**
    * @ORM\ManyToOne(targetEntity="\Parents\Entity\ParentsAssoc", inversedBy="parentsAssocTeam")
    * @ORM\JoinColumn(name="parents_assoc_id", referencedColumnName="id")
    */
    protected $parentsAssoc;
    
    
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets user ID. 
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
     * Returns full name (title + firstName + lastName).
     * @return string     
     */
    public function getFullName() {
       $fullName = $this->getTitleString() .' '. $this->firstName .' '. $this->lastName;
        return $fullName;
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
     * Returns the date of user creation.
     * @return string     
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this user was created.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }   
    
    /**
     * Returns role.
     * @return string     
     */
    public function getRole() {
        return $this->role;
    } 
    
    /**
     * Returns Role string.
     * @return string     
     */
    public function getRoleAsString() {
        $roleString = '';
        if($this->role == 1){
            $roleString = 'Chairperson';
        }
        if($this->role == 2){
            $roleString = 'Vice Chairperson';
        }
        if($this->role == 3){
            $roleString = 'Secretary';
        }
        if($this->role == 4){
            $roleString = 'Treasurer';
        }
        return $roleString;
    }

    /**
     * Sets role.
     * @param string $role
     */
    public function setRole($role) {
        $this->role = $role;
    }
    
    
    /*
    * Returns associated parentsAssoc.
    * @return \Parents\Entity\ParentsAssoc
    */
    public function getParentsAssoc() {
      return $this->parentsAssoc;
    }
    
    /**
     * Sets associated parentsAssoc.
     * @param \Parents\Entity\ParentsAssoc $parentsAssoc
     */
    public function setParentsAssoc($parentsAssoc) {
      $this->parentsAssoc = $parentsAssoc;
      $parentsAssoc->addToParentsAssocTeam($this);
    }
    
}

