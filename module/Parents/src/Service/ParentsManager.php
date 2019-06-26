<?php

namespace Parents\Service;

use Parents\Entity\ParentsInformation;
use Parents\Entity\ParentsAssoc;
use Parents\Entity\ParentsAssocTeam;
use Parents\Entity\BookList;
use Parents\Entity\Book;
use Parents\Entity\Policy;
use Parents\Entity\Stationary;
use Parents\Entity\Enrolment;
use Ourteam\Entity\Teacher;
use User\Entity\User;
use User\Entity\Season;




/**
 * This service is responsible for adding/editing elements on Parents page 
 * 
 */
class ParentsManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    
    /**
     * Season manager.
     * @var User\Service\SeasonManager
     */
    private $seasonManager;
    
    private $currentSeason;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $seasonManager) {
        $this->entityManager = $entityManager;
        $this->seasonManager = $seasonManager;
        
        $this->currentSeason = $this->seasonManager->getCurrentSeason();
    }
    
    public function getAllParentsInformation() {
        
        $parentsInformation = $this->entityManager->getRepository(ParentsInformation::class)
                     ->findBy([],['datePublished'=>'DESC']);
    
        return $parentsInformation;
    }
    
    public function getParentsAssoc(){
        
        $parentsAssoc = $this->entityManager->getRepository(ParentsAssoc::class)
                     ->find(1); 
        
        
        
        return $parentsAssoc;
    }
    
    public function getParentsAssocTeam(){
        
        //find active member (status = 1)
        $parentsAssoc = $this->entityManager->getRepository(ParentsAssocTeam::class)
                     ->findBy([],['parentsAssocRole'=>'ASC']); 
        
        
        
        return $parentsAssoc;
    }
    
    public function getBooksListCurrentSeason() {
        
        $booksListSeason = $this->entityManager->getRepository(BookList::class)
                     ->findBySeason($this->currentSeason,['level'=>'ASC']);
        
        return $booksListSeason;
        
    }
    
    public function getBooksListNextSeason() {
        
       
        $nextSeason = $this->getNextSeason();
        
         if($nextSeason != null){
            $booksListNextSeason = $this->entityManager->getRepository(BookList::class)
                     ->findBySeason($nextSeason, ['level'=>'ASC']);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error next season book list. Contact with admin.';
        
            return $dataResponse;
        }

        return $booksListNextSeason;
        
    }
    
    private function getNextSeason(){
        
        $nextSeason = $this->entityManager->getRepository(Season::class)
                     ->findOneBy(['status' => 'NEXT']);
        
         if($nextSeason == null){
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error next season. Contact with admin.';
        
            return $dataResponse;
        }
        return $nextSeason;
    }


    public function getNextSeasonName(){
        
        return $this->getNextSeason()->getSeasonName();

    }


    public function getPolicies(){
        
        $policiesList = $this->entityManager->getRepository(Policy::class)
                     ->findAll();
        
        return $policiesList;
    }
    
    
    public function getEnrolments(){
        
        $enrolment = $this->entityManager->getRepository(Enrolment::class)
                     ->findAll();
        
        return $enrolment;
        
    }


    public function saveNewParentsInformation($formData) {
        
        //create new Post
        $parentsInformation = new ParentsInformation();

        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);
        
        if($author != null){
            $parentsInformation->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
        
        //save new post other data
        $parentsInformation->setTitle($formFieldsArray['parentsInformationITitle']);
        $parentsInformation->setUrl($formFieldsArray['parentsInformationURL']);

        $current_date = date('Y-m-d H:i:s');
        $parentsInformation->setDatePublished($current_date);
        
        if(isset($_FILES['parentsInformationDoc'])){
//          $dataResponse['success'] = true;
//          $dataResponse['parentsInformationDoc'] =  $_FILES["parentsInformationDoc"]['name'];
                    
            /*
            * Save on server
            */

            //path to save
            $path_to_save_doc = './public/upload/parents/parents_information/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }
                    
            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["parentsInformationDoc"]["name"]);
                    
            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["parentsInformationDoc"]["tmp_name"], $target_file_doc);
            }else{
                //return error info
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] =  'Error. Sorry, this file has already been used. Use a different file or change the name of this file before attaching it.';
            }
                    
            /*
            * Save in db
            */
            $parentsInformation->setDocName($_FILES["parentsInformationDoc"]["name"]);
       
        }
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($parentsInformation); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Parents Information saved.';
        
        return $dataResponse;
    }
    
    
    
    public function getParentsInformationToEdit($parentsInformationID){
        
        // Find  parentsInformation with such ID.
        $parentsInformation = $this->entityManager->getRepository(ParentsInformation::class)
                ->find($parentsInformationID);
        
        if($parentsInformation !== null){
            $parentsInformationJSON = $parentsInformation->jsonSerialize();
            $dataResponse['parentsInformationToEdit'] = $parentsInformationJSON;

                
                
     
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get information to edit.';
            return $dataResponse;
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Parents Information to edit - ready.';
        
        return $dataResponse;
        
    }
    
    public function editParentsInformation($formData) {
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        // find parentsInformation to edit
        $parentsInformation  = $this->entityManager->getRepository(ParentsInformation::class)
                        ->find(intval($formFieldsArray['parentsInformationID']));
        if($parentsInformation == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find information to edit.';
            return $dataResponse;
        }
        
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);
        
        if($author != null){
            $parentsInformation->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
        
        /*
         * edit changes from regular fields
         */
        $parentsInformation->setTitle($formFieldsArray['parentsInformationTitle']);
        
        
        if($formFieldsArray['removeUrl']){
            $parentsInformation->setUrl(null);
        } 
        if($formFieldsArray['parentsInformationURL'] !== null){
            $parentsInformation->setUrl($formFieldsArray['parentsInformationURL']);
        }
        
        $current_date = date('Y-m-d H:i:s');
        $parentsInformation->setDatePublished($current_date);
        
              
        // Add the entity to the entity manager.
        $this->entityManager->persist($parentsInformation); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        /*
         * remove doc if apply
         */
        if($formFieldsArray['removeDoc']){
            //path to remove old doc
            $path_to_delete_doc = './public/upload/parents/parents_information/';
            
            // doc to delete if exist
            if($parentsInformation->getDocName() != null){
                $target_doc_to_delete = $path_to_delete_doc . $parentsInformation->getDocName();
                //remove old doc
                // if exist
                if (file_exists($target_doc_to_delete)) {
                    unlink ($target_doc_to_delete);
                }
            }
            
            $parentsInformation->setDocName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($parentsInformation); 
            
            // Apply changes to database.
            $this->entityManager->flush();
            
        }
        
        if(isset($_FILES['parentsInformationDoc'])){
//          $dataResponse['success'] = true;
//          $dataResponse['parentsInformationDoc'] =  $_FILES["parentsInformationDoc"]['name'];
                    
            /*
            * Save on server
            */

            //path to save
            $path_to_save_doc = './public/upload/parents/parents_information/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }
                    
            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["parentsInformationDoc"]["name"]);
                    
            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["parentsInformationDoc"]["tmp_name"], $target_file_doc);
            }
                    
            /*
            * Save in db
            */
            $parentsInformation->setDocName($_FILES["parentsInformationDoc"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($parentsInformation); 
            
            // Apply changes to database.
            $this->entityManager->flush();
            
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Parents Information edited.';
        
        return $dataResponse;
    }
    
    public function deleteParentsInformation($parentsInformationID) {
        
        //find parentsInformation with id
        $parentsInformation = $this->entityManager->getRepository(ParentsInformation::class)
                ->find($parentsInformationID);
        
        if($parentsInformation == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find information for parents to delete.';
            return $dataResponse;
        }
        
        //initial - prepare the path
        $path_to_delete = './public/upload/parents/parents_information/';

        
        //remove doc
        if($parentsInformation->getDocName() != null){
            $target_doc = $path_to_delete . $parentsInformation->getDocName();
            if (file_exists($target_doc)) {
                unlink ($target_doc);
            }
        }

            
        //remove from DB
        $this->entityManager->remove($parentsInformation);
        $this->entityManager->flush();

        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Parents Information deleted.';
        
        return $dataResponse;
        
    }
    
    public function addNewMemberToParentsAssocTeam($formData) {
        
        $member = new ParentsAssocTeam();
    
        $member->setTitle($formData['memberTitle']);
        $member->setParentsAssocRole($formData['memberRole']);
        $member->setFirstName($formData['memberFirstName']);
        $member->setLastName($formData['memberLastName']);
        $member->setStatus(1);

        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'New member added.';
        $dataResponse['newMemberID'] =  $member->getId();
        $dataResponse['newMemberFullName'] = $member->getFullName();
        
        return $dataResponse;
        
    }
    
    public function deleteParentsAssocTeamMember($memberID){
        
        
        //find member with id
        $member = $this->entityManager->getRepository(ParentsAssocTeam::class)
                ->find($memberID);
        
        if($member == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find member to delete.';
            return $dataResponse;
        }
        
        //get User (account) if exist
        if($member->getUser() !== null){
            $user = $this->entityManager->getRepository(User::class)
                    ->find($member->getUser());
            
            if($user !== null){
                $user->setStatus(2);
                // Add the entity to the entity manager.
                $this->entityManager->persist($user); 
                // Apply changes to database.
                $this->entityManager->flush();
            }
        }
        
        //set our team status on 2
        $member->setStatus(2);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member deleted.';
        
        return $dataResponse;
        
    }
    
    public function activateParentsAssocTeamMember($memberID){
        
        
        //find member with id
        $member = $this->entityManager->getRepository(ParentsAssocTeam::class)
                ->find($memberID);
        
        if($member == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find member to delete.';
            return $dataResponse;
        }
        
        //get User (account) if exist
        if($member->getUser() !== null){
            $user = $this->entityManager->getRepository(User::class)
                    ->find($member->getUser());
            
            if($user !== null){
                $user->setStatus(1);
                // Add the entity to the entity manager.
                $this->entityManager->persist($user); 
                // Apply changes to database.
                $this->entityManager->flush();
            }
        }
        
        //set our team status on 1
        $member->setStatus(1);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member deleted.';
        
        return $dataResponse;
        
    }
    
    public function getParentsAccocMeetingToEdit($parentsAssocID){
        
        $parentsAssoc = $this->entityManager->getRepository(ParentsAssoc::class)
                        ->find($parentsAssocID);
        
        if($parentsAssoc !== null){
            $meetingJSON = $parentsAssoc->jsonSerializeMeeting();
            $dataResponse['meetingToEdit'] = $meetingJSON;
            
            
            $dateMeeting = $parentsAssoc->getDateNextMeeting();
            $date = strtotime($dateMeeting);
        
            $dataResponse['meetingYear'] = date("Y", $date); 
            $dataResponse['meetingMonth'] = date("m", $date); 
            $dataResponse['meetingDay'] = date("d", $date);
            
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get parents assoc. meeting data to edit.';
            return $dataResponse;
        }
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Parents Assoc. found.';
        
        return $dataResponse;
    }
    
    public function editParentsAssocMeeting($editMeeting){
        
        $parentsAssoc = $this->entityManager->getRepository(ParentsAssoc::class)
                        ->find($editMeeting['parentsAssocID']);
        
        if($parentsAssoc == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find parents assoc.';
            return $dataResponse;
        }
        
        //set new valuest for parentsAssoc
        $current_date = date('Y-m-d H:i:s');
        $parentsAssoc->setDatePublished($current_date);
        
        $parentsAssoc->setLocation($editMeeting['meetingLocation']);
        
        if($editMeeting['meetingMode'] !== "0"){
            $parentsAssoc->setDateStatus($editMeeting['meetingMode']);
        }
        
        if($editMeeting['meetingMode'] === "1"){
            $parentsAssoc->setMeetingStatement($editMeeting['meetingStmt']);
            $parentsAssoc->setDateNextMeeting(null);
        }
        
        if($editMeeting['meetingMode'] === "2"){
            $meetingDate = strtotime($editMeeting['meetingDate']);   //get format m/d/Y
            $dateMeeting = date('Y-m-d H:i:s', $meetingDate);
            $parentsAssoc->setDateNextMeeting($dateMeeting);
            $parentsAssoc->setMeetingStatement(null);
        }
        $time = date("H:i", strtotime($editMeeting['meetingTime']));
        $parentsAssoc->setMeetingTime($time);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($parentsAssoc); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Meeting edited.';
        
        return $dataResponse;
        
    }
    
    public function getBookListInfo($bookListID) {
        
        //find book list
        $bookList = $this->entityManager->getRepository(BookList::class)
                        ->find($bookListID);
        
        if($bookList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find this book list.';
            return $dataResponse;
        }
        
        //find assoc. teacher
        $teacher = $this->entityManager->getRepository(User::class)
                        ->find($bookList->getTeacher());
        
        if($teacher == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find teacher for this book list.';
            return $dataResponse;
        }
   
        //find assoc. teacher
        $season = $this->entityManager->getRepository(Season::class)
                        ->find($bookList->getSeason());
        
        if($season == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find season for this book list.';
            return $dataResponse;
        }
        
        //get listOfBooks
        $listOfBooks = $bookList->getListOfBooks();
        
        $listOfSubjects = $this->getListOfSubjectsDIST($listOfBooks);
        
        $dataResponse['listOfSubjects'] = $listOfSubjects;
        
        $books = array(); 
        foreach($listOfBooks as $book){
            $bookJSON = $book->jsonSerialize();
            $books[$book->getId()] = $bookJSON;
        }
        
        $dataResponse['books'] = $books;
        
        //get listOfStationary
        $listOfStationary = $bookList->getListOfStationary();
        
        $stationaryList = array(); 
        foreach($listOfStationary as $stationary){
            $stationaryJSON = $stationary->jsonSerialize();
            $stationaryList[$stationary->getId()] = $stationaryJSON;
        }
        
        $dataResponse['stationaryList'] = $stationaryList;
        
        
        $bookListJSON = $bookList->jsonSerialize();
        $dataResponse['bookListInfo'] = $bookListJSON;
        
        $teacherJSON = $teacher->jsonSerialize();
        $dataResponse['teacherInfo'] = $teacherJSON;
        
        $seasonJSON = $season->jsonSerialize();
        $dataResponse['seasonInfo'] = $seasonJSON;
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Book List details posted.';
        
        return $dataResponse;
        
    }
    
    private function getListOfSubjectsDIST($listOfBooks){
        
        $listOfSubjects = array();

        foreach($listOfBooks as $book){
            $subject = $book->getSubject();
            if(!in_array($subject, $listOfSubjects)){
                array_push($listOfSubjects, $subject);
            }
        }
        
        
        return $listOfSubjects;
    }
    
    public function getSelectForAddBookList(){
        
        
        $dataResponse['classLevel'] = $this->getLevelTitleList();
        $dataResponse['teachers'] = $this->getAllTeachersList();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Select option for Book List.';
        
        return $dataResponse;
    }
    
    


    public function addBookList($formData){
        
        $bookList = new BookList();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        
        $bookList->setLevel($formFieldsArray['classLevel']);
        $bookList->setAdditionalMoniesInfo($formFieldsArray['additionalMonies']);
        $bookList->setUniformInfo($formFieldsArray['uniform']);
        $bookList->setOtherInfo($formFieldsArray['otherInformation']);
        
        $current_date = date('Y-m-d H:i:s');
        $bookList->setDateCreated($current_date);
        
        //set  teacher
        $teacher = $this->entityManager->getRepository(User::class)
                        ->find($formFieldsArray['teacherID']);
        
        if($teacher == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find teacher for this book list.';
            return $dataResponse;
        }       
        $bookList->setTeacher($teacher);
        
        //set author
        $author = $this->entityManager->getRepository(User::class)
                        ->find($formFieldsArray['authorID']);
        
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author for this book list.';
            return $dataResponse;
        }       
        $bookList->setAuthor($author);
        
        $bookList->setSeason($this->currentSeason);
        

        // Add the entity to the entity manager.
        $this->entityManager->persist($bookList); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        /*
         * create and save new Book objects
         */
         // json_decode to array bookList
        $formBookList = $this->decodeJSONBookListData($formData);

        foreach($formBookList as $formBook){
            
            $book = new Book();
            $book->setSubject($formBook['bookSubject']);
            $book->setTitle($formBook['bookTitle']);
            $book->setPublisher($formBook['bookPublisher']);
            $book->setBookList($bookList);

            
            // Add the entity to the entity manager.
            $this->entityManager->persist($book); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        /*
         * 
         */
        
        /*
         * create and save new Stationary objects
         */
         // json_decode to array bookList
        $formStationaryList = $this->decodeJSONStationaryListData($formData);

        foreach($formStationaryList as $formStationary){
            
            $stationary = new Stationary();
            $stationary->setName($formStationary['itemName']);
            $stationary->setQty($formStationary['itemQty']);
            
            $stationary->setBookList($bookList);

            
            // Add the entity to the entity manager.
            $this->entityManager->persist($stationary); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        /*
         * 
         */
        

        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Book List added.';
        
        return $dataResponse;
        
    }
    
    public function editBookList($formData){
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //find bookList to edit
        $bookList = $this->entityManager->getRepository(BookList::class)
                        ->find($formFieldsArray['bookListID']);
        
        if($bookList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find booklist to edit.';
            return $dataResponse;
        }
        
        $bookList->setAdditionalMoniesInfo($formFieldsArray['additionalMonies']);
        $bookList->setUniformInfo($formFieldsArray['uniform']);
        $bookList->setOtherInfo($formFieldsArray['otherInformation']);
        
        /*
         *  find author
         */
        //set author
        $author = $this->entityManager->getRepository(User::class)
                        ->find($formFieldsArray['authorID']);
        
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author for this book list.';
            return $dataResponse;
        }       
        $bookList->setAuthor($author);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($bookList); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        /**
         * remove books from bookList 
         */
        $formBooksToRemove = $this->decodeJSONBooksToRemove($formData);
        
        foreach($formBooksToRemove as $bookID){
            //get Book
            $bookToDelete = $this->entityManager->getRepository(Book::class)
                ->find(intval($bookID));
       
            
            if($bookToDelete == null){
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find book.';
                return $dataResponse;
            
            }
            
            $this->entityManager->remove($bookToDelete);
            $this->entityManager->flush();
        }
        
        /*
         * remove stationary item from bookList
         */
        $formStationaryItemToRemove = $this->decodeJSONStationaryItemToRemove($formData);
        
        foreach($formStationaryItemToRemove as $itemID){
            //get Stationary Item
            $itemToDelete = $this->entityManager->getRepository(Stationary::class)
                ->find(intval($itemID));
       
            
            if($itemToDelete == null){
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find item.';
                return $dataResponse;
            
            }
            
            $this->entityManager->remove($itemToDelete);
            $this->entityManager->flush();
        }
        
        
        /*
         * create and save new Book objects
         */
         // json_decode to array bookList
        $formBookList = $this->decodeJSONBookListData($formData);

        foreach($formBookList as $formBook){
            
            $book = new Book();
            $book->setSubject($formBook['bookSubject']);
            $book->setTitle($formBook['bookTitle']);
            $book->setPublisher($formBook['bookPublisher']);
            $book->setBookList($bookList);

            
            // Add the entity to the entity manager.
            $this->entityManager->persist($book); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        /*
         * 
         */
        
        /*
         * create and save new Stationary objects
         */
         // json_decode to array bookList
        $formStationaryList = $this->decodeJSONStationaryListData($formData);

        foreach($formStationaryList as $formStationary){
            
            $stationary = new Stationary();
            $stationary->setName($formStationary['itemName']);
            $stationary->setQty($formStationary['itemQty']);
            
            $stationary->setBookList($bookList);

            
            // Add the entity to the entity manager.
            $this->entityManager->persist($stationary); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        /*
         * 
         */
        
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Book List edited.';
        
        return $dataResponse;
        
    }
    
    
    public function deleteBookList($bookListID){
        
        //find bookList with id
        $bookList = $this->entityManager->getRepository(BookList::class)
                ->find($bookListID);
        
        if($bookList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find book list to delete.';
            return $dataResponse;
        }
        //remove all bookList books
        $listOfBooks = $bookList->getListOfBooks();
        foreach($listOfBooks as $book){
            $this->entityManager->remove($book);
        }
        
        //remove all bookList stationary
        $listOfStationary = $bookList->getListOfStationary();
        foreach($listOfStationary as $stationary){
             $this->entityManager->remove($stationary);
        }
            
        //remove from DB
        $this->entityManager->remove($bookList);
        $this->entityManager->flush();
        
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Book List deleted.';
        
        return $dataResponse;
        
    }
    
    public function saveEnrolment($formData){
        
        //create new Enrolment
        $enrolemnt = new Enrolment();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
         //save enrolment other data
        $enrolemnt->setTitle($formFieldsArray['title']);

        //save current date
        $current_date = date('Y-m-d H:i:s');
        $enrolemnt->setDatePublished($current_date);
        
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);
        
        //save author
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author.';
            return $dataResponse;
        }
        
        $enrolemnt->setAuthor($author);
        
        
        // save doc file if exist
        if(isset($_FILES['addEnrolmentDoc'])){
            
            //path to save
            $path_to_save_doc = './public/upload/parents/enrolment/';
            
            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }
            
            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["addEnrolmentDoc"]["name"]);
            
            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["addEnrolmentDoc"]["tmp_name"], $target_file_doc);
            }else{
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] =  'Sorry, this file has already been used in another enrolment document. Use a different file or change the name of this file before attaching it.';
        
                return $dataResponse;
            }
            
            // add to entity field
            $enrolemnt->setDocName($_FILES["addEnrolmentDoc"]["name"]);

            
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Problem with attached file.';
        
            return $dataResponse;
        }
        
        /*
         * Save in db (file have to be attached
         */
        // Add the entity to the entity manager.
        $this->entityManager->persist($enrolemnt); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Enrolment document saved.';

        
        return $dataResponse;
         
    }
    
    public function deleteEnrolment($id){
         
         //find enrolment with id
        $enrolment = $this->entityManager->getRepository(Enrolment::class)
                ->find($id);
        
        if($enrolment == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Enrolment form NOT found. This form can\'t be deleted.';
            
            return $dataResponse;
        }
        
        //initial - prepare the path
        //path to delete doc
        $path_to_delete_doc = './public/upload/parents/enrolment/';
        
        //target
        $target_file_doc  = $path_to_delete_doc . $enrolment->getDocName();
        
        //remove doc if exist
        if(file_exists($target_file_doc)){
            unlink ($target_file_doc);
        }
        
        //remove from DB
        $this->entityManager->remove($enrolment);
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Deleting enrolment form completed.';
        
        return $dataResponse;
        
     }
    

    public function getPolicy($policyID){
        
        
        //find policy
        $policy = $this->entityManager->getRepository(Policy::class)
                        ->find($policyID);
        
        if($policy == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find this policy.';
            return $dataResponse;
        }

        
        $policyJSON = $policy->jsonSerialize();
        $dataResponse['policy'] = $policyJSON;
        

        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Policy found.';
        
        return $dataResponse;
    }
    
    public function savePolicy($formData){
        
        
        $policy = new Policy();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);

        
        $policy->setTitle($formFieldsArray['policyTitle']);
        $policy->setContent($formFieldsArray['policyContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $policy->setDatePublished($current_date);
        
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['policyAuthor']);
        
        //save author
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author.';
            return $dataResponse;
        }
        $policy->setAuthor($author);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($policy);       
        // Apply changes to database.
        $this->entityManager->flush();
        
        if(isset($_FILES['policyDoc'])){
//          $dataResponse['success'] = true;
//          $dataResponse['addPolicyDoc'] =  $_FILES["policyDoc"]['name'];
                    
            /*
            * Save on server
            */

            //path to save
            $path_to_save_doc = './public/upload/parents/policy/'.$policy->getId().'/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }
                    
            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["policyDoc"]["name"]);
                    
            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["policyDoc"]["tmp_name"], $target_file_doc);
            }else{
                //return error info
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] =  'Error. Sorry, this file already exist. Use a different file or change the name of this file before attaching it.';
            }
                    
            /*
            * Save in db
            */
            $policy->setDocName($_FILES["policyDoc"]["name"]); 
       
        }
    
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($policy); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Policy saved.';
        
        return $dataResponse;
        
    }
    
    public function editPolicy($formData){
        
        //find policy to edit
        $policy = $this->entityManager->getRepository(Policy::class)
                        ->find($formData['policyID']); 
        
        if($policy == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find this policy.';
            return $dataResponse;
        }
        
        $policy->setTitle($formData['policyTitle']);
        $policy->setContent($formData['policyContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $policy->setDatePublished($current_date);
        
        $author = $this->entityManager->getRepository(User::class)
                ->find($formData['authorID']);
        //save author
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author.';
            return $dataResponse;
        }
        $policy->setAuthor($author);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($policy); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Policy edited.';
        
        return $dataResponse;
        
    }
    
    
    public function deletePolicy($policyID){
        
        //find policy with id
        $policy = $this->entityManager->getRepository(Policy::class)
                ->find($policyID);
        
        if($policy == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find policy to delete.';
            return $dataResponse;
        }
        
        //delete policy as file 
        if($policy->getDocName() !== null){
            //initial - prepare the path
            //path to delete doc
            $path_to_delete_doc = './public/upload/parents/policy/'.$policy->getId().'/';

            //target
            $target_file_doc  = $path_to_delete_doc . $policy->getDocName();

            //remove doc if exist
            if(file_exists($target_file_doc)){
                unlink ($target_file_doc);
            }
        }
   
        //remove from DB
        $this->entityManager->remove($policy);
        $this->entityManager->flush();

        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Policy deleted.';
        
        return $dataResponse;
        
    }


    
    // return title for each level
    private function getLevelTitleList() {
        
        $titlesList = ['---select class ---', '1st Class', '1st - 2nd Class', '2nd Class', '2nd - 3rd Class', '3rd Class', '3rd - 4th Class', '4th Class', '4th - 5th Class', '5th Class', '5th - 6th Class', '6th Class'];
        $keysList = ['0', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0'];
        
        
        
        for($i=0; $i<count($titlesList); $i++){
            $levelTitleList[$keysList[$i]] = $titlesList[$i];
        }
        
      
        return $levelTitleList;
    }
    
    //return list of all teachers
    private function getAllTeachersList(){
        
        $teacherList = $this->entityManager->getRepository(Teacher::class)
                     ->findByStatus(1);
        
        $teachersWithAccountList = array();
        $teachersWithAccountList[0] = '--- select teacher ---';
        
        if($teacherList === null){
            return $teachersWithAccountList;
        }
        
        
        
        //get only with account User
        foreach($teacherList as $teacher){
            if($teacher->getUser() !== null){
                //array_push($teachersWithAccountList, $teacher->getUser());
                $teachersWithAccountList[$teacher->getUser()->getId()] = $teacher->getFullName();
            }
        }
        
        return $teachersWithAccountList;
        
        
    }
    
    private function decodeJSONdata($formData){
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        return $formFieldsArray;
    }
    
    /*
     * Decode array in array
     */
    private function decodeJSONBookListData($formData){
        
        $size = sizeof((array)json_decode($formData['booksArr']));
        $list_of_books = array();
        if($size > 0){
            for($i=0; $i<$size; $i++){
                array_push($list_of_books, (array)json_decode($formData['booksArr'])[$i]);
            }
        }
        
        return $list_of_books;
        
    }
    
    /*
     * Decode array in array
     */
    private function decodeJSONStationaryListData($formData){
        
        $size = sizeof((array)json_decode($formData['stationaryArr']));
        $list_of_stationary = array();
        if($size > 0){
            for($i=0; $i<$size; $i++){
                array_push($list_of_stationary, (array)json_decode($formData['stationaryArr'])[$i]);
            }
        }
        
        return $list_of_stationary;
        
    }
    
    /*
     * Decode array to int
     */
    private function decodeJSONBooksToRemove($formData){
        $size = sizeof((array)json_decode($formData['books_to_remove']));
        $books_to_remove_list = array();
        if($size > 0){
            for($i=0; $i<$size; $i++){
                array_push($books_to_remove_list, (int)json_decode($formData['books_to_remove'])[$i]);
            }
        }
        
        return $books_to_remove_list;
    }
    
    /*
     * Decode array to int
     */
    private function decodeJSONStationaryItemToRemove($formData){
        $size = sizeof((array)json_decode($formData['stationary_to_remove']));
        $stationary_to_remove_list = array();
        if($size > 0){
            for($i=0; $i<$size; $i++){
                array_push($stationary_to_remove_list, (int)json_decode($formData['stationary_to_remove'])[$i]);
            }
        }
        
        return $stationary_to_remove_list;
    }
    
   
    
}

