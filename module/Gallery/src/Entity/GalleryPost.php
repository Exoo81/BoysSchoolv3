<?php

namespace Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Classes\Entity\Post;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap( {"gallery_post" = "GalleryPost", "class_post" = "Classes\Entity\ClassPost"} )
 */
class GalleryPost extends Post {

    
    
    /** 
    * @ORM\Column(name="video_name")  
    */
    protected $videoName;
    
    
    /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\PostImage", mappedBy="post")
    * @ORM\JoinColumn(name="id", referencedColumnName="post_id")
    */
     protected $postGallery;
    
    
    
    /**
    * Constructor.
    */
    public function __construct() {
        $this->postGallery = new ArrayCollection();
    }
    
    
    
    /**
     * Returns galleryPost videoName.
     * @return string
     */
    public function getVideoName() {
        return $this->videoName;
    }

    /**
     * Sets galleryPost videoName. 
     * @param string $videoName    
     */
    public function setVideoName($videoName) {
        $this->videoName = $videoName;
    }
    
    
    
    /**
    * Returns post gallery.
    * @return array
    */
    public function getPostGallery() {
      return $this->postGallery;
    }
    
    /**
     * Returns the string of assigned as postGallery id.
     */
    public function getPostGalleryAsString(){
        $postGalleryList = '';
        
        $count = count($this->postGallery);
        $i = 0;
        foreach ($this->postGallery as $postImage) {
            $postGalleryList .= $postImage->getId();
            if ($i<$count-1)
                $postGalleryList .= ', ';
            $i++;
        }
        
        return $postGalleryList;
    }
    
    /**
     * Adds a new picture to this post.
     * @param $photo
     */
    public function addPhotoToPostGallery($photo) {
      $this->postGallery[] = $photo;
    }
    
    
    
    
    
    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'videoName' => $this->getVideoName()
        ];
    }
    
    
}

