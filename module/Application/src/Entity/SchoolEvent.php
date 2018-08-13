<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="school_event")
 */
class SchoolEvent {
    
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
     * @ORM\Column(name="date_event")  
     */
    protected $dateEvent;
  
    /** 
     * @ORM\Column(name="date_published")  
     */
    protected $datePublished;
  
    /** 
     * @ORM\Column(name="location")  
     */
    protected $location;
  
    /** 
     * @ORM\Column(name="content")  
     */
    protected $content;
  
    /** 
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="eventList")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
  
  
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="eventsList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
  
  
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns event title.
     * @return integer
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets evetn title. 
     * @param String $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    /**
     * Returns event date.
     * @return string
     */
    public function getDateEvent() {
        return $this->dateEvent;
    }

    /**
     * Sets event date. 
     * @param string $dateEvent    
     */
    public function setDateEvent($dateEvent) {
        $this->dateEvent = $dateEvent;
    }
    /**
     * Returns event published date.
     * @return integer
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets event date published. 
     * @param string $datePublished  
     */
    public function setDatePublished($datePublished) {
        $this->datePublished = $datePublished;
    }
    /**
     * Returns event location.
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Sets event location. 
     * @param string $location    
     */
    public function setLocation($location) {
        $this->location = $location;
    }
    /**
     * Returns event content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets event content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
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
      $season->addEventToList($this);
    }
    
    
    /*
    * Returns associated user.
    * @return \User\Entity\User
    */
    public function getAuthor() {
      return $this->author;
    }
    
    /**
     * Sets associated user.
     * @param \User\Entity\User $user
     */
    public function setAuthor($user) {
      $this->author = $user;
      $user->addEventToList($this);
    }
    
    
    
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'dateEvent' => $this->getDateEvent(),
            'dateCreated' => $this->getDatePublished(),
            'location' => $this->getLocation(),
        ];
    }
}

