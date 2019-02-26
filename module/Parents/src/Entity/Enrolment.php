<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="enrolment")
 */
class Enrolment {
    
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
    * @ORM\Column(name="date_published")  
    */
    protected $datePublished;
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;  
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="enrolmentList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
    
    /**
     * Returns enrolment ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets enrolment ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns enrolment title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets enrolment title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    
    /**
     * Returns enrolment published date.
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets enrolment published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
    }
    
    /**
     * Returns enrolment doc name..
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets enrolment file name. 
     * @param string $docName    
     */
    public function setDocName($docName) {
        $this->docName = $docName;
    }
    
    /*
    * Returns associated user.
    * @return \User\Entity\User
    */
    public function getAuthor() {
      return $this->author;
    }
    
    /**
     * Sets associated user.
     * @param \User\Entity\User $user
     */
    public function setAuthor($user) {
      $this->author = $user;
      $user->addEnrolmentToList($this);
    }
    
}

