<?php
namespace Parents\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parents_association")
 */
class ParentsAssoc {
    
     /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(name="id")   
    */
    protected $id;
    
    /**
     * @ORM\Column(name="date_published")  
     */
    protected $datePublished;
    
    /** 
     * @ORM\Column(name="location")  
     */
    protected $location;
    
    /** 
     * @ORM\Column(name="date_status")  
     */
    protected $dateStatus;
    
    /**
     * @ORM\Column(name="date_next_meeting")  
     */
    protected $dateNextMeeting;
    
    /**
     * @ORM\Column(name="meeting_statement")  
     */
    protected $meetingStatement;
    
    /**
     * @ORM\Column(name="meeting_time")  
     */
    protected $meetingTime;
    
    
    
    /**
     * Constructor.
     */
    public function __construct() {
        $this->parentsAssocTeam = new ArrayCollection();
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
     * Returns parentAssoc published date..
     * @return string
     */
    public function getDatePublished() {
        return $this->datePublished;
    }

    /**
     * Sets parentAssoc published date. 
     * @param string $date_published    
     */
    public function setDatePublished($date_published) {
        $this->datePublished = $date_published;
    }
    
    /**
     * Returns parentAssoc location.
     * @return string
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * Sets parentAssoc location. 
     * @param string $location    
     */
    public function setLocation($location) {
        $this->location = $location;
    }
    
    /**
     * Returns dateStatus.
     * @return string     
     */
    public function getDateStatus() {
        return $this->dateStatus;
    } 
    
    /**
     * Returns dateStatus string.
     * @return string     
     */
    public function getDateStatusAsString() {
        $dateStatusString = '';
        if($this->dateStatus == 1){
            $dateStatusString = 'AUTO';
        }
        if($this->dateStatus == 2){
            $dateStatusString = 'MANUAL';
        }
        return $dateStatusString;
    }

    /**
     * Sets dateStatus.
     * @param string $dateStatus
     */
    public function setDateStatus($dateStatus) {
        $this->dateStatus = $dateStatus;
    }
    
    /**
     * Returns parentAssoc next meeting date..
     * @return string
     */
    public function getDateNextMeeting() {
        return $this->dateNextMeeting;
    }

    /**
     * Sets parentAssoc next meeting date. 
     * @param string $nextMeetingDate    
     */
    public function setDateNextMeeting($nextMeetingDate) {
        $this->dateNextMeeting = $nextMeetingDate;
    }
    
    
    
    /**
     * Returns parentAssoc meetingStatement.
     * @return string
     */
    public function getMeetingStatement() {
        return $this->meetingStatement;
    }

    /**
     * Sets parentAssoc meetingStatement. 
     * @param string $stmt    
     */
    public function setMeetingStatement($stmt) {
        $this->meetingStatement = $stmt;
    }
    
    /**
     * Returns parentAssoc meeting time.
     * @return string
     */
    public function getMeetingTime() {
        return $this->meetingTime;
    }

    /**
     * Sets parentAssoc meeting time. 
     * @param string $meetingTime    
     */
    public function setMeetingTime($meetingTime) {
        $this->meetingTime = $meetingTime;
    }
    

//     /**
//    * Returns parents Assoc Team for this parentsAssoc.
//    * @return array
//    */
//    public function getParentsAssocTeam() {
//      return $this->parentsAssocTeam;
//    }
//    
//    /**
//     * Returns the string of assigned as parents Assoc Team id.
//     */
//    public function getParentsAssocTeamAsString(){
//        $list = '';
//        
//        $count = count($this->parentsAssocTeam);
//        $i = 0;
//        foreach ($this->parentsAssocTeam as $member) {
//            $list .= $member->getId();
//            if ($i<$count-1)
//                $list .= ', ';
//            $i++;
//        }
//        
//        return $list;
//    }
//    
//    /**
//     * Adds a new member of parents assoc team to parentsAssocTeam in this parentsAssoc.
//     * @param $member
//     */
//    public function addToParentsAssocTeam($member) {
//      $this->parentsAssocTeam[] = $member;
//    }
     
    
    
    public function jsonSerializeMeeting(){
        $convert_time = date("g:i a", strtotime($this->getMeetingTime()));
        return 
        [
            'id'   => $this->getId(),
            'location' => $this->getLocation(),
            'dateStatus' => $this->getDateStatus(),
            'dateNextMeeting' => $this->getDateNextMeeting(),
            'meetingStmt' => $this->getMeetingStatement(),
            'meetingTime' => $convert_time
        ];
    }
    
}

