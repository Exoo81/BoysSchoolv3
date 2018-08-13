<?php

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Classes\Entity\Post;
use Gallery\Entity\GalleryPost;

/**
 * This class represents a class post.
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class ClassPost extends GalleryPost {
    
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;


    /**
     * Returns post docName.
     * @return string
     */
    public function getDocName() {
        return $this->docName;
    }

    /**
     * Sets post docName. 
     * @param string $docName    
     */
    public function setDocName($docName) {
        $this->docName = $docName;
    }
    

    
    public function jsonSerialize(){
        return 
        [
            'id'   => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'docName' => $this->getDocName(),
            'videoName' => $this->getVideoName()
        ];
    }
    
}

