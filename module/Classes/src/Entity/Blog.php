<?php

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a blog entity.
 * @ORM\Entity()
 * @ORM\Table(name="blog")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap( {"blog" = "Blog", "class_blog" = "ClassBlog", "gallery_blog" = "Gallery\Entity\GalleryBlog", "news_blog" = "Application\Entity\NewsBlog"} )
 */
class Blog {
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /** 
    * @ORM\ManyToOne(targetEntity="\User\Entity\Season", inversedBy="classBlogsList")
    * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
    */
    protected $season;
    
    /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Post", mappedBy="blog")
    * @ORM\JoinColumn(name="id", referencedColumnName="blog_id")
    */
     protected $postList;
    
    /**
   * Constructor.
   */
    public function __construct() {
        $this->postList = new ArrayCollection();
    }
    
    /**
     * Returns Blog ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets Blog ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
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
      $season->addClassBlogToList($this);
    }
    
  
    /**
    * Returns post for this blog.
    * @return array
    */
    public function getPostList() {
      return $this->postList;
    }
    
    /**
     * Returns the string of assigned as post id.
     */
    public function getPostListAsString(){
        $postList = '';
        
        $count = count($this->postList);
        $i = 0;
        foreach ($this->postList as $post) {
            $postList .= $post->getId();
            if ($i<$count-1)
                $postList .= ', ';
            $i++;
        }
        
        return $postList;
    }
    
    /**
     * Adds a new post to this blog.
     * @param $post
     */
    public function addPostToList($post) {
      $this->postList[] = $post;
    }
    
       
}

