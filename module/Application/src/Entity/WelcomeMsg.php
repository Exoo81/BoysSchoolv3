<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="welcome_msg")
 */
class WelcomeMsg {
    
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
     protected $id;  
    
    /** 
    * @ORM\Column(name="content")  
    */
    protected $content;
    
    /**
     * Returns welcome message ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets welcome message ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns welcome message content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets welcome message content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'content' => $this->getContent(),
        ];
    }
}

