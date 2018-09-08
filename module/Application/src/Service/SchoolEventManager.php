<?php

namespace Application\Service;

use Application\Entity\SchoolEvent;
use User\Entity\User;



/**
 * This service is responsible for adding/editing/delete school events
 * 
 */
class SchoolEventManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    private $currentSeason;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $seasonManager) {
        $this->entityManager = $entityManager;
        $this->seasonManager = $seasonManager;
        
        $this->currentSeason = $this->seasonManager->getCurrentSeason();
    }
    
    public function getSeasonEvents(){
        
        $schoolEvents = $this->entityManager->getRepository(SchoolEvent::class)
                     ->findBySeason($this->currentSeason, ['dateEvent'=>'DESC']);
               
        return $schoolEvents;
        
    }
    
    public function getCurrentEvents($selectedDate){
              
        $current_date = date("Y-m-d",strtotime($selectedDate));
   
        $currentEvents = $this->entityManager->getRepository(SchoolEvent::class)
                ->findBy(array('dateEvent' => $current_date));
        
        $currentEventsArray = array();
        foreach($currentEvents as $event){
            array_push($currentEventsArray, $event->jsonSerialize());
        }

        
        $dataResponse['currentEvents'] =  $currentEventsArray;
        
        $dataResponse['eventsNumberPerDayInMonth'] = $this->getAllEventsNumberFromCurrentMonth();

        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Events from a given day' ;
        
        return $dataResponse;
    }
    
    //return number of events for every day from current month
    private function getAllEventsNumberFromCurrentMonth(){
        
        $first_day_of_vurrent_month = date("Y-m-d",strtotime('first day of this month'));
        $last_day_of_vurrent_month = date("Y-m-d",strtotime('last day of this month'));
        
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
    
        $queryBuilder->select('e')
            ->from(SchoolEvent::class, 'e')
            ->where('e.dateEvent >= :first_day' )
            ->andWhere('e.dateEvent <= :last_day')
            ->orderBy('e.dateEvent', 'ASC')
                ->setParameter('first_day', $first_day_of_vurrent_month)
                ->setParameter('last_day', $last_day_of_vurrent_month);
        $query = $queryBuilder->getQuery()->getArrayResult();
        
        $eventsNumberPerDayInMonth = array();
        
        //create array with dateEvent key and add null
        foreach($query as $eventInMonth){
            
            //convert dateEvent ($key) to format eg. 2018-5-31 (without 0) - format in calendar.js
            $dateEventString  = date("Y-n-j",strtotime($eventInMonth['dateEvent']));
            
            if(array_key_exists($dateEventString ,$eventsNumberPerDayInMonth)){
                $eventNumber = $eventsNumberPerDayInMonth[$dateEventString];
                $eventsNumberPerDayInMonth[$dateEventString] = $eventNumber + 1;
            }else{
                $eventsNumberPerDayInMonth[$dateEventString] = 1;
            }
        }

        
        $dataResponse['eventsNumberPerDayInMonth'] = $eventsNumberPerDayInMonth;
        
        return $dataResponse['eventsNumberPerDayInMonth'];
        
    }
    
    public function getFullCalendarPage($year, $month){

        $dataResponse['eventsNumberPerDayInMonth'] = $this->getAllEventsNumberFromSelectedMonth($year, $month);
//        $dataResponse['startYear'] = $this->currentSeason->getStarYear();
        $dataResponse['season'] = $this->currentSeason->jsonSerialize();
        $dataResponse['success'] = true;

        return $dataResponse;
    }
    
    private function getAllEventsNumberFromSelectedMonth($year, $month){
        
        $dateString = $year.'/'.$month.'/1';
        $first_day_of_current_month = date("Y-m-01",strtotime($dateString));
        $last_day_of_current_month = date("Y-m-t",strtotime($dateString));
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
    
        $queryBuilder->select('e')
            ->from(SchoolEvent::class, 'e')
            ->where('e.dateEvent >= :first_day' )
            ->andWhere('e.dateEvent <= :last_day')
            ->orderBy('e.dateEvent', 'ASC')
                ->setParameter('first_day', $first_day_of_current_month)
                ->setParameter('last_day', $last_day_of_current_month);
        $query = $queryBuilder->getQuery()->getArrayResult();
        
        $eventsNumberPerDayInMonth = array();
        
        //create array with dateEvent key and add null
        foreach($query as $eventInMonth){
            
            //convert dateEvent ($key) to format eg. 2018-5-31 (without 0) - format in calendar.js
            $dateEventString  = date("Y-n-j",strtotime($eventInMonth['dateEvent']));
            
            if(array_key_exists($dateEventString ,$eventsNumberPerDayInMonth)){
                $eventNumber = $eventsNumberPerDayInMonth[$dateEventString];
                $eventsNumberPerDayInMonth[$dateEventString] = $eventNumber + 1;
            }else{
                $eventsNumberPerDayInMonth[$dateEventString] = 1;
            }
        }

        
        $dataResponse['eventsNumberPerDayInMonth'] = $eventsNumberPerDayInMonth;
        
        return $dataResponse['eventsNumberPerDayInMonth'];
        
    }
    
    public function getEvent($id){
        
        $event = $this->entityManager->getRepository(SchoolEvent::class)
                ->find($id);
        
       
        if($event != null){
            $dataResponse['event'] =  $event->jsonSerialize();
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Event NOT found.';
        }
        
        $dateEvent = $event->getDateEvent();
        $date = strtotime($dateEvent);
        $eventYear = date("Y", $date);
        
        $dataResponse['eventYear'] = date("Y", $date); 
        $dataResponse['eventMonth'] = date("m", $date); 
        $dataResponse['eventDay'] = date("d", $date); 
        
        $dataResponse['success'] = true;
        $dataResponse['colorBox'] = $this->getRandomColorBox();
             
        return $dataResponse;
    }
    
    private function getRandomColorBox(){
        $colorBoxList = array("red-box","purpure-box","blue-box","orange-box","green-box", "yellow-box", "brown-box");
        $random_keys=array_rand($colorBoxList,1);
        $colorBox = $colorBoxList[$random_keys];
        return $colorBox;
    }
    
//    private function convertDateEvent($dateDB){
//        $dateEventString  = date("d-M-Y",strtotime($dateDB));
//        return $dateEventString;
//    }
    
    public function saveEvent($formData){
        
        //create new Event
        $event = new SchoolEvent();
        
        $author = $this->entityManager->getRepository(User::class)
                ->find($formData['authorID']);
        
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author.';
            return $dataResponse;
        }
        
        $event->setAuthor($author);
        
        //save news other data
        $event->setSeason($this->currentSeason);
        $event->setTitle($formData['title']);
        $event->setContent($formData['content']);
        $event->setLocation($formData['location']);
        
        $current_date = date('Y-m-d H:i:s');
        $event->setDatePublished($current_date);
        
        $eventDate = strtotime($formData['eventDate']);   //get format m/d/Y
        $dateEvent = date('Y-m-d H:i:s', $eventDate);
        $event->setDateEvent($dateEvent);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($event); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Event saved.';
        
        return $dataResponse;
        
    }
    
    public function editEvent($dataForm){
        
        
        
        //find old to edit
        $editEvent = $this->entityManager->getRepository(SchoolEvent::class)
                ->find($dataForm['id']);
        
        if($editEvent == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Event not found. Event can\'t be edited.';
        
            return $dataResponse;
        }
        /*
         * edit changes from all fields
         */

        $editEvent->setTitle($dataForm['title']);

        $eventDate = strtotime($dataForm['eventDate']);   //get format m/d/Y
        $dateEvent = date('Y-m-d H:i:s', $eventDate);
        $editEvent->setDateEvent($dateEvent);
        
        $editEvent->setLocation($dataForm['location']);
        
        //current date
//        $current_date = date('Y-m-d H:i:s');
//        $editEvent->setDatePublished($current_date);
        
        $editEvent->setContent($dataForm['content']);
        
        
        //save in DB
        // Add the entity to the entity manager.
        $this->entityManager->persist($editEvent);
        
        // Apply changes to database.
        $this->entityManager->flush();

        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Event edited.';
        
        return $dataResponse;
        
    }
    
    public function deleteEvent($dataForm){

        //find news with id
        $deleteEvent = $this->entityManager->getRepository(SchoolEvent::class)
                ->find($dataForm['id']);
        
        if($deleteEvent == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Event not found. Event can\'t be deleted.';
        
            return $dataResponse;
        }


        //remove from DB
        $this->entityManager->remove($deleteEvent);
        $this->entityManager->flush();

        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Event deleted.';
        
        return $dataResponse;
        
    }
    
}

