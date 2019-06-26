<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="books")
 */
class Book {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /** 
    * @ORM\Column(name="subject") 
    */
    protected $subject;
    
    /** 
    * @ORM\Column(name="title")  
    */
    protected $title;
    
    /** 
    * @ORM\Column(name="publisher")  
    */
    protected $publisher;
    
    
    /** 
    * @ORM\ManyToOne(targetEntity="\Parents\Entity\BookList", inversedBy="listOfBooks")
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
     * Returns book subject.
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Sets book subject. 
     * @param string $subject    
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }
    
    /**
     * Returns book title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets book title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    
    /**
     * Returns book publisher.
     * @return string
     */
    public function getPublisher() {
        return $this->publisher;
    }

    /**
     * Sets book publisher. 
     * @param string $publisher   
     */
    public function setPublisher($publisher) {
        $this->publisher = $publisher;
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
      $bookList->addBookToListOfBooks($this);
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'subject'   => $this->getSubject(),
            'title' => $this->getTitle(),
            'publisher'   => $this->getPublisher()
        ];
    }
    
}

