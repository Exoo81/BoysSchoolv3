<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="about_us")
 */
class AboutUs {
    
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
    * @ORM\Column(name="principal_name")  
    */
    protected $principalName;
    
    
     /**
     * Returns about us ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets about us ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns about us title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets about us title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns about us content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets about us content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Returns principal name.
     * @return string
     */
    public function getPrincipalName() {
        return $this->principalName;
    }

    /**
     * Sets principal name. 
     * @param string $principalName 
     */
    public function setPrincipalName($principalName) {
        $this->principalName = $principalName;
    }
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title'   => $this->getTitle(),
            'content' => $this->getContent(),
            'principalName' => $this->getPrincipalName(),
        ];
    }
}

