<?php
/*
*   PostImage (entity) - represent. of single picture related One-To-Many with Post
*/

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="post_image")
 */
class PostImage {
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    
    /** 
    * @ORM\Column(name="photo_name")  
    */
    protected $photoName;
    
    
    /** 
    * @ORM\ManyToOne(targetEntity="\Classes\Entity\Post", inversedBy="postGallery")
    * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
    */
    protected $post;
    
    
    /**
     * Returns gallery ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets gallery ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Returns gallery photo name
     * @return string
     */
    public function getPhotoName() {
        return $this->photoName;
    }

    /**
     * Sets gallery photo name 
     * @param string $photo_name    
     */
    public function setPhotoName($photo_name) {
        $this->photoName = $photo_name;
    }
    
    /*
    * Returns associated post.
    * @return \Classes\Entity\Post
    */
    public function getPost() {
      return $this->post;
    }
    
    /**
     * Sets associated post.
     * @param \Classes\Entity\Post $post
     */
    public function setPost($post) {
      $this->post = $post;
      $post->addPhotoToPostGallery($this);
    }
    
}

