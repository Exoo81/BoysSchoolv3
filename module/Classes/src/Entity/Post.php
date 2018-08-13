<?php

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a post entity.
 * @ORM\Entity()
 * @ORM\Table(name="post")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap( {"post" = "Post", "gallery_post" = "Gallery\Entity\GalleryPost"} )
 */
class Post {
    

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
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
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="classPostsList")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="postsList")
    * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
    */
    protected $author;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\Classes\Entity\Blog", inversedBy="postList")
    * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
    */
    protected $blog;
    

    
    /**
     * Returns post ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets post ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns post title.
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets post title. 
     * @param string $title    
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns post content.
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Sets post content. 
     * @param string $content    
     */
    public function setContent($content) {
        $this->content = $content;
    }
    
    
    /**
     * Returns post published date
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }
    
    /**
     * Sets post published date 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
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
      $season->addClassPostToList($this);
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
      $user->addPostToList($this);
    }
    

    /*
    * Returns associated blog.
    * @return \Classes\Entity\Blog
    */
    public function getBlog() {
      return $this->blog;
    }
    
    /**
     * Sets associated blog.
     * @param \Classes\Entity\Blog $blog
     */
    public function setBlog($blog) {
      $this->blog = $blog;
      $blog->addPostToList($this);
    }
    
    
      
    
}