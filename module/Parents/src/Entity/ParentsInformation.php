<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parents_information")
 */
class ParentsInformation {
    
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
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;
    
    /** 
    * @ORM\Column(name="url")  
    */
    protected $url;
    
    /** 
     * @ORM\Column(name="date_published")  
     */
    protected $datePublished;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="parentsInformationList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
    
    
    
    
    
    
    /**
     * Returns ParentsInformation ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets ParentsInformation ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns ParentsInformation title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets ParentsInformation title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    
    /**
     * Returns ParentsInformation docName.
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets ParentsInformation docName. 
     * @param string $docName    
     */
    public function setDocName($docName) {
        $this->docName = $docName;
    }
    
     /**
     * Returns ParentsInformation url.
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Sets ParentsInformation url. 
     * @param string $url    
     */
    public function setUrl($url) {
        $this->url = $url;
    }
    
    /**
     * Returns ParentsInformation published date..
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets ParentsInformation published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
    }
    
     /*
    * Returns ParentsInformation user.
    * @return \User\Entity\User
    */
    public function getAuthor() {
      return $this->author;
    }
    
    /**
     * Sets ParentsInformation user.
     * @param \User\Entity\User $user
     */
    public function setAuthor($user) {
      $this->author = $user;
      $user->addParentsInformationToList($this);
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title' => $this->getTitle(),
            'docName' => $this->getDocName(),
            'url' => $this->getUrl(),
        ];
    }
    
}

