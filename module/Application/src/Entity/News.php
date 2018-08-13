<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News implements \JsonSerializable {
    
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
    * @ORM\Column(name="content")  
    */
    protected $content;
  
    /** 
    * @ORM\Column(name="date_published")  
    */
    protected $datePublished;
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;
  
    /** 
    * @ORM\Column(name="photo_name")  
    */
    protected $photoName;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="news")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="newsList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
  
    /**
     * Returns news ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets news ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns news title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets news title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns news content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets news content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Returns news published date..
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets news published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
    }
    
    
    /**
     * Returns news docName.
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets news docName. 
     * @param string $docName    
     */
    public function setDocName($docName) {
        $this->docName = $docName;
    }
    
    
    /**
     * Returns news photo name..
     * @return string
     */
    public function getPhotoName() {
        return $this->photoName;
    }

    /**
     * Sets news photo name. 
     * @param string $photo_name    
     */
    public function setPhotoName($photo_name) {
        $this->photoName = $photo_name;
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
      $season->addNews($this);
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
      $user->addNewsToList($this);
    }
    
    
    
    
    
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'currentSeason' => $this->getSeason()->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'photoName' => $this->getPhotoName(),
            'docName' => $this->getDocName(),
        ];
    }
    
}

