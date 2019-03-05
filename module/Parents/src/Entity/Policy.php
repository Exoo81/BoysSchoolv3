<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="policy")
 */
class Policy {
    
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
    * @ORM\Column(name="content")  
    */
    protected $content;
    
    /**
     * @ORM\Column(name="date_published")  
     */
    protected $datePublished;
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="policyList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
    
    
    /**
     * Returns policy ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets policy ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns policy title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets policy title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns policy content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets policy content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Returns the date of policy published.
     * @return string     
     */
    public function getDatePublished() {
        return $this->datePublished;
    }
    
    /**
     * Sets the date when this policy was published/edited.
     * @param string $datePublished     
     */
    public function setDatePublished($datePublished) {
        $this->datePublished = $datePublished;
    }
    
    /**
     * Returns Policy docName.
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets Policy docName. 
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
      $user->addPolicyToList($this);
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title'   => $this->getTitle(),
            'content' => $this->getContent(),
            'date_published' => $this->getDatePublished()
        ];
    }
}

