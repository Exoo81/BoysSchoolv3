<?php

namespace Schoollife\Service;


use Schoollife\Entity\SchoolLife;
use Classes\Entity\Post;
use User\Entity\User;
use Ourteam\Entity\Teacher;
use Ourteam\Entity\ASDUnit;
use Ourteam\Entity\LearningSupport;
use User\Entity\Season;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;



/**
 * This service is responsible for adding/editing etc. school life activit.
 * 
 */
class SchoolLifeManager{
    
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
    
    public function getActiveSchoolLife(){
        
        
        $schoolLifeList = $this->entityManager->getRepository(SchoolLife::class)
                     ->findByStatus(1);
        
        if($schoolLifeList != null){
            $schoolLifeListLeftRight = $this->splitSchoolLifeToLeftAndRight($schoolLifeList);
            
            $schoolLifeColorList = $this->getLevelColorList($schoolLifeList);
            
            $fullListsOfSchoolLife = array();
            
            $fullListsOfSchoolLife['schoolLife-right'] = $schoolLifeListLeftRight['right'];
            $fullListsOfSchoolLife['schoolLife-left'] = $schoolLifeListLeftRight['left'];
            $fullListsOfSchoolLife['colors'] = $schoolLifeColorList;
//            mobile
            $fullListsOfSchoolLife['schoolLife'] = $schoolLifeList;
            
        }else{
            $schoolLifeList = null;
        }
        
        return $fullListsOfSchoolLife;
    }
    
    public function getSchoolLife($schoolLifeID){
        //find schoolLife
        $schoolLife = $this->entityManager->getRepository(SchoolLife::class)
                        ->find($schoolLifeID);
        
        if($schoolLife == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find this school life.';
            return $dataResponse;
        }

        
        $schoolLifeJSON = $schoolLife->jsonSerialize();
        $dataResponse['schoolLife'] = $schoolLifeJSON;
        
        $schoolLifePhotoPath = 'upload/school-life/dummy.jpg';
        if($schoolLife->getPhotoName() != null){
            $schoolLifePhotoPath = 'upload/school-life/'.$schoolLife->getId().'/'.$schoolLife->getPhotoName();
        }
        
        $dataResponse['schoolLifePhotoPath'] = $schoolLifePhotoPath;

        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'School Life found.';
        
        return $dataResponse;
    }
    
    public function editSchoolLife($formData){
        
        // json_decode to array
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //find school life to edit
        $editSchoolLife = $this->entityManager->getRepository(SchoolLife::class)
                ->find($formFieldsArray['schoolLifeID']);
        
        if($editSchoolLife == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: School Life not found. School Life can\'t be edited.';
        
            return $dataResponse;
        }
        
        /*
         * edit changes from regular fields
         */
        $editSchoolLife->setTitle($formFieldsArray['schoolLifeTitle']);
        $editSchoolLife->setStatus($formFieldsArray['schoolLifeStatus']);
        $editSchoolLife->setContent($formFieldsArray['schoolLifeContent']);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($editSchoolLife); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        /*
         *  remove photo if apply
         */
        if($formFieldsArray['removePhoto']){
            
            //path to remove old photo
            $path_to_delete_photo = './public/upload/school-life/'.$editSchoolLife->getId().'/';
            
            // photo to delete if exist
            if($editSchoolLife->getPhotoName() != null){
                $target_photo_to_delete = $path_to_delete_photo . $editSchoolLife->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }
            
            $editSchoolLife->setPhotoName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($editSchoolLife); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        // save class photo file if posted by form
        if(isset($_FILES['editSchoolLifePhoto'])){ 
//            $dataResponse['success'] = true;
//            $dataResponse['editSchoolLifePhoto'] =  $_FILES["editSchoolLifePhoto"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to remove/save
            $path_to_photo = './public/upload/school-life/'.$editSchoolLife->getId().'/';

            // save new photo form input file field
            //check if dir exist else - create
            if(!is_dir($path_to_photo)) {
                 mkdir($path_to_photo, 0777, true);
            }
            
            //remove old photo if not null (only when input file field choosen without click on remove button "X"
            // photo to delete if exist
            if($editSchoolLife->getPhotoName() != null){
                $target_photo_to_delete = $path_to_photo . $editSchoolLife->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }

            //$target new photo
            $target_file_photo = $path_to_photo . basename($_FILES["editSchoolLifePhoto"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_photo)) {
                //save on server
                move_uploaded_file($_FILES["editSchoolLifePhoto"]["tmp_name"], $target_file_photo);
            }

            /*
            * Save in db
            */
            $editSchoolLife->setPhotoName($_FILES["editSchoolLifePhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($editSchoolLife); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Edit "School Life" completed.';
        
        return $dataResponse;
        
    }


    private function splitSchoolLifeToLeftAndRight($schoolLifeList){
        
        $schoolLifeListLeftRight = array();
        $schoolLifeRightList = array();
        $schoolLifeLeftList = array();
        
        $moduloCounter = 0;
        foreach($schoolLifeList as $schoolLife){
            if($moduloCounter % 2){
                array_push($schoolLifeLeftList, $schoolLife);
                
            }else{
                array_push($schoolLifeRightList, $schoolLife);
            }
            
            $moduloCounter++;
        }
        
        $schoolLifeListLeftRight['right'] = $schoolLifeRightList;
        $schoolLifeListLeftRight['left'] = $schoolLifeLeftList;
        
        return $schoolLifeListLeftRight;
        
    }
    
    // return color for each level
    private function getLevelColorList($schoolLifeList) {
        
        $colorList = ['blue', 'red', 'yellow', 'green', 'orange', 'purpure', 'blue','red', 'yellow', 'green', 'orange', 'purpure'];
        $schoolLifeColorList = null;
        $count = 0;
        foreach ($schoolLifeList as $schoolLife){
            $key = $schoolLife->getId();
            $schoolLifeColorList[$key] = $colorList[$count];
            
            if($count>=11){
                $count = 0;
            }else{
                $count++;
            }
        }
      
        return $schoolLifeColorList;
    }
    
    private function decodeJSONdata($formData){
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        return $formFieldsArray;
    }
    
}

