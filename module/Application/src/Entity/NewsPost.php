<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Classes\Entity\Post;


/**
 * This class represents a class post.
 * @ORM\Entity()
 * @ORM\Table(name="post")
 */
class NewsPost extends Post {
    
    /** 
    * @ORM\Column(name="doc_name")  
    */
    protected $docName;
  
    /** 
    * @ORM\Column(name="photo_name")  
    */
    protected $photoName;
    
    
    
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
    
}

