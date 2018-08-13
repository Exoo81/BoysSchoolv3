<?php

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User {
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="email")  
     */
    protected $email;
    
    /** 
     * @ORM\Column(name="title")  
     */
    protected $title;
    
    /** 
     * @ORM\Column(name="first_name")  
     */
    protected $firstName;
    
    /** 
     * @ORM\Column(name="last_name")  
     */
    protected $lastName;

    /** 
     * @ORM\Column(name="password")  
     */
    protected $password;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
        
    /**
     * @ORM\Column(name="pwd_reset_token")  
     */
    protected $passwordResetToken;
    
    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")  
     */
    protected $passwordResetTokenCreationDate;
    
    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;
    
    
    
    
    
    /**
    * @ORM\OneToMany(targetEntity="\Application\Entity\News", mappedBy="author")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $newsList;
     
     
     /**
    * @ORM\OneToMany(targetEntity="\Application\Entity\SchoolEvent", mappedBy="author")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $eventsList;
     
     
    
    /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Blog", mappedBy="teacher")
    * @ORM\JoinColumn(name="id", referencedColumnName="teacher_id")
    */
     protected $teacherBlogs;
     
     /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Blog", mappedBy="learningSupport")
    * @ORM\JoinColumn(name="id", referencedColumnName="learningSupport_id")
    */
     protected $learningSupportBlogs;
    
    
    /**
    * @ORM\OneToMany(targetEntity="\Classes\Entity\Post", mappedBy="author")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $postsList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Gallery\Entity\GalleryPost", mappedBy="author")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $galleriesList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Parents\Entity\ParentsInformation", mappedBy="author")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $parentsInformationList;
     
     /**
    * @ORM\OneToMany(targetEntity="\Parents\Entity\BookList", mappedBy="teacher")
    * @ORM\JoinColumn(name="id", referencedColumnName="author_id")
    */
     protected $bookList;
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->roles = new ArrayCollection();
        $this->teacherBlogs = new ArrayCollection();
        $this->learningSupportBlogs = new ArrayCollection();
        $this->newsList = new ArrayCollection();
        $this->eventsList = new ArrayCollection();
        $this->postsList = new ArrayCollection();
        $this->galleriesList = new ArrayCollection();
        $this->parentsInformationList = new ArrayCollection();
        $this->bookList = new ArrayCollection();
    }
    
      
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Returns email.     
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Sets email.     
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }
    
    /**
     * Returns title.
     * @return string     
     */
    public function getTitle() {
        return $this->title;
    } 
    
    /**
     * Returns title string.
     * @return string     
     */
    public function getTitleString() {
        $titleString = '';
        if($this->title == 1){
            $titleString = 'Mr.';
        }
        if($this->title == 2){
            $titleString = 'Mrs.';
        }
        if($this->title == 3){
            $titleString = 'Ms.';
        }
        return $titleString;
    }

    /**
     * Sets title.
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }
    
    /**
     * Returns first name.
     * @return string     
     */
    public function getFirstName() {
        return $this->firstName;
    }       

    /**
     * Sets first name.
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }
    
    /**
     * Returns last name.
     * @return string     
     */
    public function getLastName() {
        return $this->lastName;
    }       

    /**
     * Sets last name.
     * @param string $lastName
     */
    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }
    
    /**
     * Returns full name.
     * @return string     
     */
    public function getFullName() {
       $fullName = $this->getTitleString() .' '. $this->firstName .' '. $this->lastName;
        return $fullName;
    }       

    /**
     * Sets full name.
     * @param string $fullName
     */
    public function setFullName($fullName) {
        $this->fullName = $fullName;
    }
    
    /**
     * Returns title + last name.
     * @return string     
     */
    public function getTitleLastName() {
       $titleLastName = $this->getTitleString() .' '. $this->lastName;
        return $titleLastName;
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
            self::STATUS_RETIRED => 'Retired'
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
     * Returns password.
     * @return string
     */
    public function getPassword() {
       return $this->password; 
    }
    
    /**
     * Sets password.     
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }
    
    /**
     * Returns the date of user creation.
     * @return string     
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this user was created.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }    
    
    /**
     * Returns password reset token.
     * @return string
     */
    public function getResetPasswordToken(){
        return $this->passwordResetToken;
    }
    
    /**
     * Sets password reset token.
     * @param string $token
     */
    public function setPasswordResetToken($token) {
        $this->passwordResetToken = $token;
    }
    
    /**
     * Returns password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate(){
        return $this->passwordResetTokenCreationDate;
    }
    
    /**
     * Sets password reset token's creation date.
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate($date) {
        $this->passwordResetTokenCreationDate = $date;
    }
    
    /**
     * Returns the array of roles assigned to this user.
     * @return array
     */
    public function getRoles(){
        return $this->roles;
    }
    
    /**
     * Returns the string of assigned role names.
     */
    public function getRolesAsString(){
        $roleList = '';
        
        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();
            if ($i<$count-1)
                $roleList .= ', ';
            $i++;
        }
        
        return $roleList;
    }
    
    /**
     * Assigns a role to user.
     */
    public function addRole($role){
        $this->roles->add($role);
    }
    
    
    /**
    * Returns news list for this user.
    * @return array
    */
    public function getNewsList() {
      return $this->newsList;
    }
    
    /**
     * Returns the string of assigned as news id.
     */
    public function getNewsListAsString(){
        $newsList = '';
        
        $count = count($this->newsList);
        $i = 0;
        foreach ($this->newsList as $news) {
            $newsList .= $news->getId();
            if ($i<$count-1)
                $newsList .= ', ';
            $i++;
        }
        
        return $newsList;
    }
    
    /**
     * Adds a new news to newsList in this user.
     * @param $news
     */
    public function addNewsToList($news) {
      $this->newsList[] = $news;
    }
    
    
    
    
    
     /**
    * Returns events list for this user.
    * @return array
    */
    public function getEventsList() {
      return $this->eventsList;
    }
    
    /**
     * Returns the string of assigned as event id.
     */
    public function getEventListAsString(){
        $eventsList = '';
        
        $count = count($this->eventsList);
        $i = 0;
        foreach ($this->eventsList as $event) {
            $eventsList .= $event->getId();
            if ($i<$count-1)
                $eventsList .= ', ';
            $i++;
        }
        
        return $eventsList;
    }
    
    /**
     * Adds a new event to eventsList in this user.
     * @param $event
     */
    public function addEventToList($event) {
      $this->eventsList[] = $event;
    }
    
    
    
    
    
    
    
    
    
    
    /**
    * Returns teacher blogs for this user.
    * @return array
    */
    public function getTecherBlogs() {
      return $this->teacherBlogs;
    }
    
    /**
     * Returns the string of assigned as teacher blog id.
     */
    public function getTeacherBlogsAsString(){
        $blogList = '';
        
        $count = count($this->teacherBlogs);
        $i = 0;
        foreach ($this->teacherBlogs as $blog) {
            $blogList .= $blog->getId();
            if ($i<$count-1)
                $blogList .= ', ';
            $i++;
        }
        
        return $blogList;
    }
    
    /**
     * Adds a new teacher blog to this user.
     * @param $blog
     */
    public function addTeacherBlog($blog) {
      $this->teacherBlogs[] = $blog;
    }
    
    
    /**
    * Returns learning support blogs for this user.
    * @return array
    */
    public function getLearningSupportBlogs() {
      return $this->learningSupportBlogs;
    }
    
    /**
     * Returns the string of assigned as tlearning support blog id.
     */
    public function getLearningSupportBlogsAsString(){
        $blogList = '';
        
        $count = count($this->learningSupportBlogs);
        $i = 0;
        foreach ($this->learningSupportBlogs as $blog) {
            $blogList .= $blog->getId();
            if ($i<$count-1)
                $blogList .= ', ';
            $i++;
        }
        
        return $blogList;
    }
    
    /**
     * Adds a new learning support blog to this user.
     * @param $blog
     */
    public function addLearningSupportBlog($blog) {
      $this->learningSupportBlogs[] = $blog;
    }
    
    
    /**
    * Returns posts list for this user.
    * @return array
    */
    public function getPostsList() {
      return $this->postsList;
    }
    
    /**
     * Returns the string of assigned as post id.
     */
    public function getPostsListAsString(){
        $postsList = '';
        
        $count = count($this->postsList);
        $i = 0;
        foreach ($this->postsList as $post) {
            $postsList .= $post->getId();
            if ($i<$count-1)
                $postsList .= ', ';
            $i++;
        }
        
        return $postsList;
    }
    
    /**
     * Adds a new post to postsList in this user.
     * @param $post
     */
    public function addPostToList($post) {
      $this->postsList[] = $post;
    }
    
    
    
    
    
    
    
    /**
    * Returns galleries list for this user.
    * @return array
    */
    public function getGalleriesList() {
      return $this->galleriesList;
    }
    
    /**
     * Returns the string of assigned as post id.
     */
    public function getGalleriesListAsString(){
        $galleriesPostList = '';
        
        $count = count($this->galleriesList);
        $i = 0;
        foreach ($this->galleriesList as $galleryPost) {
            $galleriesPostList .= $galleryPost->getId();
            if ($i<$count-1)
                $galleriesPostList .= ', ';
            $i++;
        }
        
        return $galleriesPostList;
    }
    
    /**
     * Adds a new galleryPost to galleriesList in this user.
     * @param $galleryPost
     */
    public function addGalleryPostToList($galleryPost) {
      $this->galleriesList[] = $galleryPost;
    }
    
    
    
    /**
    * Returns ParentsInformation list for this user.
    * @return array
    */
    public function getParentsInformationList() {
      return $this->parentsInformationList;
    }
    
    /**
     * Returns the string of assigned as ParentsInformation id.
     */
    public function getParentsInformationListAsString(){
        $pi_List = '';
        
        $count = count($this->parentsInformationList);
        $i = 0;
        foreach ($this->parentsInformationList as $parentsInformation) {
            $pi_List .= $parentsInformation->getId();
            if ($i<$count-1)
                $pi_List .= ', ';
            $i++;
        }
        
        return $pi_List;
    }
    
    /**
     * Adds a ParentsInformation to parentsInformationList in this user.
     * @param $parentsInformation
     */
    public function addParentsInformationToList($parentsInformation) {
      $this->parentsInformationList[] = $parentsInformation;
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
            'full_name' => $this->getFullName()
        ];
    }
    
    
    
}

