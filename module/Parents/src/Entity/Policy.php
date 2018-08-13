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

