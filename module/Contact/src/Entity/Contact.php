<?php
namespace Contact\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact")
 */
class Contact {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /** 
    * @ORM\Column(name="school_name") 
    */
    protected $schoolName;
    
    /** 
    * @ORM\Column(name="address") 
    */
    protected $address;
    
    /** 
    * @ORM\Column(name="email") 
    */
    protected $email;
    
    /** 
    * @ORM\Column(name="phone_main") 
    */
    protected $phoneMain;
    
    /** 
    * @ORM\Column(name="phone_alt_1") 
    */
    protected $phoneAlt1;
    
    /** 
    * @ORM\Column(name="phone_alt_2") 
    */
    protected $phoneAlt2;
    
    
    /** 
    * @ORM\Column(name="www_url") 
    */
    protected $wwwURL;
    
    
    
    /**
     * Returns contact ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets contact ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns contact school name.
     * @return string
     */
    public function getSchoolName() {
        return $this->schoolName;
    }

    /**
     * Sets contact school name. 
     * @param string $schoolName    
     */
    public function setSchoolName($schoolName) {
        $this->schoolName = $schoolName;
    }
    
    /**
     * Returns contact address.
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Sets contact address. 
     * @param string $address    
     */
    public function setAddress($address) {
        $this->address = $address;
    }
    
    
    /**
     * Returns contact email.
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets contact email. 
     * @param string $email    
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    
    
     /**
     * Returns contact main phone number.
     * @return string
     */
    public function getPhoneMain() {
        return $this->phoneMain;
    }

    /**
     * Sets contact main phone number. 
     * @param string $phoneMain    
     */
    public function setPhoneMain($phoneMain) {
        $this->phoneMain = $phoneMain;
    }
    
    /**
     * Returns contact alt 1 phone number.
     * @return string
     */
    public function getPhoneAlt1() {
        return $this->phoneAlt1;
    }

    /**
     * Sets contact alt 1 phone number. 
     * @param string $phoneAlt1    
     */
    public function setPhoneAlt1($phoneAlt1) {
        $this->phoneAlt1 = $phoneAlt1;
    }
    
    
    /**
     * Returns contact alt 2 phone number.
     * @return string
     */
    public function getPhoneAlt2() {
        return $this->phoneAlt2;
    }

    /**
     * Sets contact alt 2 phone number. 
     * @param string $phoneAlt2    
     */
    public function setPhoneAlt2($phoneAlt2) {
        $this->phoneAlt2 = $phoneAlt2;
    }
   
    
    /**
     * Returns contact www.
     * @return string
     */
    public function getWwwUrl() {
        return $this->wwwURL;
    }

    /**
     * Sets contact www. 
     * @param string $wwwURL   
     */
    public function setWwwUrl($wwwURL) {
        $this->wwwURL = $wwwURL;
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'school_name' => $this->getSchoolName(),
            'address'   => $this->getAddress(),
            'email' => $this->getEmail(),
            'phone_main'   => $this->getPhoneMain(),
            'phone_alt1'   => $this->getPhoneAlt1(),
            'phone_alt2'   => $this->getPhoneAlt2(),
            'www'   => $this->getWwwUrl()
        ];
    }
    
    
}

