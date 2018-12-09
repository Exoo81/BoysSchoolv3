<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="newsletter")
 */
class Newsletter {
    
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
    * @ORM\Column(name="date_newsletter")  
    */
    protected $dateNewsletter;
    
    /** 
    * @ORM\Column(name="date_published")  
    */
    protected $datePublished;
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="newslettersList")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
    
    
    
    
    /**
     * Returns newsletter ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets newsletter ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns newsletter title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets newsletter title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns newsletter  date.
     * @return string
     */
    public function getDateNewsletter() {
        return $this->dateNewsletter;
    }

    /**
     * Sets newsletter  date. 
     * @param string $date_newsletter    
     */
    public function setDateNewsletter($date_newsletter) {
        $this->dateNewsletter = $date_newsletter;
    }
    
    /**
     * Returns newsletter published date.
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets newsletter published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
    }
    
    /**
     * Returns newsletter doc name..
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets newsletter file name. 
     * @param string $docName    
     */
    public function setDocName($docName) {
        $this->docName = $docName;
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
      $season->addNewsletterToList($this);
    }
}

