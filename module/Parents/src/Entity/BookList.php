<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_list")
 */
class BookList {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /** 
     * @ORM\Column(name="level")  
     */
    protected $level;
    
    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
    
    /** 
     * @ORM\Column(name="additional_monies_info")  
     */
    protected $additionalMoniesInfo;
    
    /** 
     * @ORM\Column(name="uniform_info")  
     */
    protected $uniformInfo;
    
    /** 
     * @ORM\Column(name="other_info")  
     */
    protected $otherInfo;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="bookList")
    * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
    */
    protected $teacher;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="bookList")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
    
    
    /**
    * @ORM\OneToMany(targetEntity="\Parents\Entity\Book", mappedBy="bookList")
    * @ORM\JoinColumn(name="id", referencedColumnName="bookList_id")
    */
    protected $listOfBooks;
    
    /**
    * @ORM\OneToMany(targetEntity="\Parents\Entity\Stationary", mappedBy="bookList")
    * @ORM\JoinColumn(name="id", referencedColumnName="bookList_id")
    */
    protected $listOfStationary;
    
    
    
   /**
   * Constructor.
   */
    public function __construct() {
        $this->listOfBooks = new ArrayCollection();
        $this->listOfStationary = new ArrayCollection();
    }
    
    
    
    
    /**
     * Returns bookList ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets bookList ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
   
    
    /**
     * Returns bookList level .
     * @return float
     */
    public function getLevel() {
        return $this->level;
    }
    
    /*
     * Returns bookList level as string
     */
    public function getLevelAsString(){
        
        $levelTitleList = null;
        
        $titlesList = ['1st Class', '1st - 2nd Class', '2nd Class', '2nd - 3rd Class', '3rd Class', '3rd - 4th Class', '4th Class', '4th - 5th Class', '5th Class', '5th - 6th Class', '6th Class'];
        $keysList = ['1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0'];
        
        for($i=0; $i<count($titlesList); $i++){
            $levelTitleList[$keysList[$i]] = $titlesList[$i];
        }

        return $levelTitleList[$this->getLevel()];
    }

    /**
     * Sets bookList level. 
     * @param float $level    
     */
    public function setLevel($level) {
        $this->level = $level;
    }

    
    /**
     * Returns the date of bookList creation.
     * @return string     
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this bookList was created.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    } 
    
    /**
     * Returns bookList's additionalMoniesInfo .     
     * @return string
     */
    public function getAdditionalMoniesInfo() {
        return $this->additionalMoniesInfo;
    }

    /**
     * Sets bookList's additionalMoniesInfo.     
     * @param string $additionalMoniesInfo
     */
    public function setAdditionalMoniesInfo($additionalMoniesInfo) {
        $this->additionalMoniesInfo = $additionalMoniesInfo;
    }
    
    
    /**
     * Returns bookList's uniformInfo .     
     * @return string
     */
    public function getUniformInfo() {
        return $this->uniformInfo;
    }

    /**
     * Sets bookList's uniformInfo.     
     * @param string $uniformInfo
     */
    public function setUniformInfo($uniformInfo) {
        $this->uniformInfo = $uniformInfo;
    }
    
    /**
     * Returns bookList's otherInfo .     
     * @return string
     */
    public function getOtherInfo() {
        return $this->otherInfo;
    }

    /**
     * Sets bookList's otherInfo.     
     * @param string $otherInfo
     */
    public function setOtherInfo($otherInfo) {
        $this->otherInfo = $otherInfo;
    }
    
    
    /*
    * Returns associated teacher.
    * @return \User\Entity\User
    */
    public function getTeacher() {
      return $this->teacher;
    }
    
    /**
     * Sets associated teacher.
     * @param \User\Entity\User $teacher
     */
    public function setTeacher($teacher) {
      $this->teacher = $teacher;
      $teacher->addBookListToList($this);
    }
    
    /*
    * Returns associated season.
    * @return \User\Entity\Season
    */
    public function getSeason() {
      return $this->season;
    }
    
    /**
     * Sets associated season.
     * @param \User\Entity\Season $season
     */
    public function setSeason($season) {
      $this->season = $season;
      $season->addBookListToList($this);
    }
    

    /**
    * Returns listOfBooks for this bookList.
    * @return array
    */
    public function getListOfBooks() {
      return $this->listOfBooks;
    }
    
    /**
     * Returns the string of assigned as book id.
     */
    public function getListOfBooksAsString(){
        $list = '';
        
        $count = count($this->listOfBooks);
        $i = 0;
        foreach ($this->listOfBooks as $book) {
            $list .= $book->getId();
            if ($i<$count-1)
                $list .= ', ';
            $i++;
        }
        
        return $list;
    }
    
    /**
     * Adds a new book to this listOfBooks.
     * @param $book
     */
    public function addBookToListOfBooks($book) {
      $this->listOfBooks[] = $book;
    }
    
    
     
    /**
    * Returns listOfStationary for this bookList.
    * @return array
    */
    public function getListOfStationary() {
      return $this->listOfStationary;
    }
    
    /**
     * Returns the string of assigned as stationary id.
     */
    public function getListOfStationaryAsString(){
        $list = '';
        
        $count = count($this->listOfStationary);
        $i = 0;
        foreach ($this->listOfStationary as $stationary) {
            $list .= $stationary->getId();
            if ($i<$count-1)
                $list .= ', ';
            $i++;
        }
        
        return $list;
    }
    
    /**
     * Adds a new stationary to this listOfBooks.
     * @param $stationary
     */
    public function addStationaryToListOfStationary($stationary) {
      $this->listOfStationary[] = $stationary;
    }
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'level' => $this->getLevelAsString(),
            'date_created' => $this->getDateCreated(),
            'additional_monies_info' => $this->getAdditionalMoniesInfo(),
            'uniform_info' => $this->getUniformInfo(),
            'other_info' => $this->getOtherInfo()
        ];
    }
    
}

