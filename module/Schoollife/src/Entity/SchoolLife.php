<?php
namespace Schoollife\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="school_life")
 */
class SchoolLife {
    
    // School Life status constants.
    const STATUS_ACTIVE       = 1; // Active school life.
    const STATUS_INACTIVE      = 2; // Inactive school life.
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /** 
    * @ORM\Column(name="title")  
    */
    protected $title;
    
    /** 
    * @ORM\Column(name="content")  
    */
    protected $content;
    
    /** 
    * @ORM\Column(name="photo_name")  
    */
    protected $photoName;
    
    /** 
    * @ORM\Column(name="date_published")  
    */
    protected $datePublished;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="schoolLifeList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
    
    
    
    
    
    
    /**
     * Returns school life ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets school life ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns status.
     * @return int     
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList() {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive'
        ];
    }    
    
    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString(){
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];
        
        return 'Unknown';
    }    
    
    /**
     * Sets status.
     * @param int $status     
     */
    public function setStatus($status) {
        $this->status = $status;
    }
    
    /**
     * Returns school life title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets school life title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns school life content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets school life content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    /**
     * Returns school life photo name..
     * @return string
     */
    public function getPhotoName() {
        return $this->photoName;
    }

    /**
     * Sets school life photo name. 
     * @param string $photo_name    
     */
    public function setPhotoName($photo_name) {
        $this->photoName = $photo_name;
    }
    
    /**
     * Returns school life published date..
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets school life published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
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
      $user->addSchoolLifeToList($this);
    }
    
    
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'status' => $this->getStatus(),
            'title'   => $this->getTitle(),
            'content' => $this->getContent(),
            'photoName' => $this->getPhotoName(),
            'date_published' => $this->getDatePublished()
        ];
    }
}


