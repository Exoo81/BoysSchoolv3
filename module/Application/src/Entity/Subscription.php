<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscription")
 */
class Subscription {
    
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
     protected $id;
     
    /** 
    * @ORM\Column(name="email")  
    */
    protected $email;
    
    
    
    /**
     * Returns subscription ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets subscription ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns subscription email.
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets subscription email. 
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
}

