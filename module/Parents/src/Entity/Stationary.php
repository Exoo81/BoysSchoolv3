<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="stationary")
 */
class Stationary {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /** 
    * @ORM\Column(name="name") 
    */
    protected $name;
    
    /** 
    * @ORM\Column(name="qty") 
    */
    protected $qty;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\Parents\Entity\BookList", inversedBy="listOfStationary")
    * @ORM\JoinColumn(name="bookList_id", referencedColumnName="id")
    */
    protected $bookList;
    
    
    
    
    
    /**
     * Returns book ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets book ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns stationary name.
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Sets stationary name. 
     * @param string $name   
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    
    /**
     * Returns stationary qty.
     * @return integer
     */
    public function getQty() {
        return $this->qty;
    }

    /**
     * Sets stationary qty. 
     * @param int $qty    
     */
    public function setQty($qty) {
        $this->qty = $qty;
    }
    
    
    
    /*
    * Returns associated bookList.
    * @return \Parents\Entity\BookList
    */
    public function getBookList() {
      return $this->bookList;
    }
    
    /**
     * Sets associated bookList.
     * @param \Parents\Entity\BookList $bookList
     */
    public function setBookList($bookList) {
      $this->bookList = $bookList;
      $bookList->addStationaryToListOfStationary($this);
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'name'   => $this->getName(),
            'qty' => $this->getQty(),
        ];
    }
    
}

