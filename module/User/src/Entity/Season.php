<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * This class represents a season.
 * @ORM\Entity()
 * @ORM\Table(name="season")
 */
class Season{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    /** 
     * @ORM\Column(name="season_name")  
     */
    protected $seasonName;
    
    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
    * @ORM\OneToMany(targetEntity="\Application\Entity\News", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $news;
     
     /**
    * @ORM\OneToMany(targetEntity="\Application\Entity\SchoolEvent", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $eventsList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Application\Entity\Newsletter", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $newslettersList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Blog", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $classBlogsList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Post", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $classPostsList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Parents\Entity\BookList", mappedBy="season")
    * @ORM\JoinColumn(name="id", referencedColumnName="season_id")
    */
     protected $bookList;
     
     /**
   * Constructor.
   */
    public function __construct() {
        $this->news = new ArrayCollection();
        $this->eventsList = new ArrayCollection();
        $this->newslettersList = new ArrayCollection();
        $this->classBlogsList = new ArrayCollection();
        $this->classPostsList = new ArrayCollection();
        $this->bookList = new ArrayCollection();
    }
    
    
    /*
     * 
     * Getters and setters
     * 
     */
    
    /**
     * Returns season ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }
    /**
     * Sets season ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns seasonName.     
     * @return string
     */
    public function getSeasonName() {
        return $this->seasonName;
    }

    /**
     * Sets seasonName.     
     * @param string $seasonName
     */
    public function setSeasonName($seasonName) {
        $this->seasonName = $seasonName;
    }
    
    /**
     * Returns status.     
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Sets status.     
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }
    
    
    
    
    /**
    * Returns  news list for this season.
    * @return array
    */
    public function getNews() {
      return $this->news;
    }
    
    /**
     * Returns the string of assigned as news id.
     */
    public function getNewsAsString(){
        $newsList = '';
        
        $count = count($this->news);
        $i = 0;
        foreach ($this->news as $singleNews) {
            $newsList .= $singleNews->getId();
            if ($i<$count-1)
                $newsList .= ', ';
            $i++;
        }
        
        return $newsList;
    }
    
    /**
     * Add a new single news to this season.
     * @param $news
     */
    public function addNews($news) {
      $this->news[] = $news;
    }
    
    
    
    /**
    * Returns  events list for this season.
    * @return array
    */
    public function getEvents() {
      return $this->eventsList;
    }
    
    /**
     * Returns the string of assigned as event id.
     */
    public function getEventsListAsString(){
        $eventList = '';
        
        $count = count($this->eventsList);
        $i = 0;
        foreach ($this->eventsList as $event) {
            $eventList .= $event->getId();
            if ($i<$count-1)
                $eventList .= ', ';
            $i++;
        }
        
        return $eventList;
    }
    
    /**
     * Add a new school event to this season.
     * @param $event
     */
    public function addEventToList($event) {
      $this->eventsList[] = $event;
    }
    
    
    /**
    * Returns  newsletters list for this season.
    * @return array
    */
    public function getNewsletters() {
      return $this->newslettersList;
    }
    
    /**
     * Returns the string of assigned as newsletter id.
     */
    public function getNewslettersListAsString(){
        $newsletttersList = '';
        
        $count = count($this->newslettersList);
        $i = 0;
        foreach ($this->newslettersList as $newsletter) {
            $newsletttersList .= $newsletter->getId();
            if ($i<$count-1)
                $newsletttersList .= ', ';
            $i++;
        }
        
        return $newsletttersList;
    }
    
    /**
     * Add a new newsletter to this season.
     * @param $newsletter
     */
    public function addNewsletterToList($newsletter) {
      $this->newslettersList[] = $newsletter;
    }
    
    
    /**
    * Returns  class blogs list for this season.
    * @return array
    */
    public function getClassBlogs() {
      return $this->classBlogsList;
    }
    
    /**
     * Returns the string of assigned as class blogs id.
     */
    public function getClassBlogsListAsString(){
        $classBlogsList = '';
        
        $count = count($this->classBlogsList);
        $i = 0;
        foreach ($this->classBlogsList as $classBlog) {
            $classBlogsList .= $classBlog->getId();
            if ($i<$count-1)
                $classBlogsList .= ', ';
            $i++;
        }
        
        return $classBlogsList;
    }
    
    /**
     * Add a new class blog to this season.
     * @param $classBlog
     */
    public function addClassBlogToList($classBlog) {
      $this->classBlogsList[] = $classBlog;
    }
    
    
    /**
    * Returns  posts list for this season.
    * @return array
    */
    public function getClassPosts() {
      return $this->classPostsList;
    }
    
    /**
     * Returns the string of assigned as class posts id.
     */
    public function getClassPostsListAsString(){
        $classPostsList = '';
        
        $count = count($this->classPostsList);
        $i = 0;
        foreach ($this->classPostsList as $classPost) {
            $classPostsList .= $classPost->getId();
            if ($i<$count-1)
                $classPostsList .= ', ';
            $i++;
        }
        
        return $classPostsList;
    }
    
    /**
     * Add a new cpost to this season.
     * @param $classPost
     */
    public function addClassPostToList($classPost) {
      $this->classPostsList[] = $classPost;
    }
    
    
    /**
    * Returns bookList for this user.
    * @return array
    */
    public function getBookList() {
      return $this->bookList;
    }
    
    /**
     * Returns the string of assigned as bookList id.
     */
    public function getBookListAsString(){
        $bookList = '';
        
        $count = count($this->bookList);
        $i = 0;
        foreach ($this->bookList as $bookListObj) {
            $bookList .= $bookListObj->getId();
            if ($i<$count-1)
                $bookList .= ', ';
            $i++;
        }
        
        return $bookList;
    }
    
    /**
     * Adds a new bookListObj to bookList in this user.
     * @param $bookList
     */
    public function addBookListToList($bookList) {
      $this->bookList[] = $bookList;
    }
    
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'season_name' => $this->getSeasonName()
        ];
    }
}

