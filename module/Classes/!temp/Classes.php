<?php

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="classes")
 */
class Classes {
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** 
     * @ORM\Column(name="content")  
     */
    protected $content;         //list of objects
    
    
    
    
    /**
     * Returns Classes ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets Classes ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns Classes content (blogs list).
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets Classes content (blogs list). 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
       
}

