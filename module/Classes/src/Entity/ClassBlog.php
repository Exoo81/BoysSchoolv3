<?php

namespace Classes\Entity;

use Doctrine\ORM\Mapping as ORM;
use Classes\Entity\Blog;


/**
 * This class represents a class blog entity.
 * @ORM\Entity()
 * @ORM\Table(name="blog")
 */
class ClassBlog extends Blog {
    
    
    
    /** 
     * @ORM\Column(name="level")  
     */
    protected $level;
    
    
    /** 
     * @ORM\Column(name="photo_name")  
     */
    protected $photoName;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="teacherBlogs")
    * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
    */
    protected $teacher;
    
    /**
    * @ORM\ManyToOne(targetEntity="\User\Entity\User", inversedBy="learningSupportBlogs")
    * @ORM\JoinColumn(name="learningSupport_id", referencedColumnName="id")
    */
    protected $learningSupport;
    
    
    
    /**
     * Returns Blog level .
     * @return float
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * Sets Blog level. 
     * @param float $level    
     */
    public function setLevel($level) {
        $this->level = $level;
    }
    
    /**
     * Returns blog photo name..
     * @return string
     */
    public function getPhotoName() {
        return $this->photoName;
    }

    /**
     * Sets blog photo name. 
     * @param string $photo_name    
     */
    public function setPhotoName($photo_name) {
        $this->photoName = $photo_name;
    }
    
    /*
    * Returns associated user.
    * @return \User\Entity\User
    */
    public function getTeacher() {
      return $this->teacher;
    }
    
    /**
     * Sets associated user.
     * @param \User\Entity\User $user
     */
    public function setTeacher($user) {
      $this->teacher = $user;
      $user->addTeacherBlog($this);
    }
    
    /*
   * Returns associated user.
   * @return \User\Entity\User
   */
    public function getLearningSupport() {
      return $this->learningSupport;
    }
    
    /**
     * Sets associated user.
     * @param \User\Entity\User $user
     */
    public function setLearningSupport($user) {
        
      if($user !== null){
        $this->learningSupport = $user;
        $user->addLearningSupportBlog($this);
      }else{
        $this->learningSupport = null;
        //$user->removeLearningSupportBlog($this);
      }
    }
    
    
    
    
    /*
     * Returns class level as string
     */
    public function getLevelAsString(){
        
        $levelTitleList = null;
        
        $titlesList = ['1st Class', '1st - 2nd Class', '2nd Class', '2nd - 3rd Class', '3rd Class', '3rd - 4th Class', '4th Class', '4th - 5th Class', '5th Class', '5th - 6th Class', '6th Class', 'ASD Class'];
        $keysList = ['1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0', '7.0'];
        
        for($i=0; $i<count($titlesList); $i++){
            $levelTitleList[$keysList[$i]] = $titlesList[$i];
        }

        return $levelTitleList[$this->getLevel()];
    }
    
    
}

