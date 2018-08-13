<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="our_awards")
 */

class OurAwards {
    
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
    * @ORM\Column(name="photo_name")  
    */
    protected $photoName;
    
    
    
    
    /**
     * Returns award ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets award ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns award title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets award title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns award published date.
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets award published date. 
     * @param string $datePublished    
     */
    public function setDatePublished($datePublished) {
        $this->datePublished = $datePublished;
    }
    
    /**
     * Returns award photo name..
     * @return string
     */
    public function getPhotoName() {
        return $this->photoName;
    }

    /**
     * Sets award photo name. 
     * @param string $photoName    
     */
    public function setPhotoName($photoName) {
        $this->photoName = $photoName;
    }
}

