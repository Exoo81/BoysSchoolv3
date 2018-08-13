<?php

namespace Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="blog")
 */
class GalleryBlog {
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="\User\Entity\Season")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */
    protected $season;
    
    /**
    * @ORM\OneToMany(targetEntity="\Gallery\Entity\GalleryPost", mappedBy="blog")
    * @ORM\JoinColumn(name="id", referencedColumnName="gallery_blog_id")
    */
     protected $posts;
     
    /**
    * Constructor.
    */
    public function __construct() {
        $this->posts = new ArrayCollection();
    }
    
    /**
     * Returns galleryBlog ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets galleryBlog ID. 
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
    }
    
    /**
    * Returns gallery post for this blog.
    * @return array
    */
    public function getPosts() {
      return $this->posts;
    }
    
    /**
     * Returns the string of assigned as galleryPost id.
     */
    public function getPostsAsString(){
        $postList = '';
        
        $count = count($this->posts);
        $i = 0;
        foreach ($this->posts as $post) {
            $postList .= $post->getId();
            if ($i<$count-1)
                $postList .= ', ';
            $i++;
        }
        
        return $postList;
    }
    
    /**
     * Adds a new galleryPost to this blog.
     * @param $galleryPost
     */
    public function addPost($galleryPost) {
      $this->posts[] = $galleryPost;
    }
    
}

