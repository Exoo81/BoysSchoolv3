<?php

namespace Application\Service;

use Application\Entity\OurAwards;



/**
 * This service is responsible for adding/delete school awards
 * 
 */
class OurAwardsManager{
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    
    public function addOurAward($formData){
        
        //create new News
        $award = new OurAwards();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //save news other data
        $award->setTitle($formFieldsArray['title']);
        
        $current_date = date('Y-m-d H:i:s');
        $award->setDatePublished($current_date);
        
         // save doc file if exist
        if(isset($_FILES['ourAwardPhoto'])){
            
//            $dataResponse['success'] = true;
//            $dataResponse['newsDoc'] =  $_FILES["ourAwardPhoto"]['name'];
//            return $dataResponse;
            
            /*
            * Save on server
            */

            //path to save award photo
            $path_to_save_photo = './public/upload/awards/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_photo)) {
                mkdir($path_to_save_photo, 0777, true);
            }
            
            //$target
            $target_file_photo = $path_to_save_photo . basename($_FILES["ourAwardPhoto"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_photo)) {
                //save on server
                move_uploaded_file($_FILES["ourAwardPhoto"]["tmp_name"], $target_file_photo);
            }

            /*
            * Save in db
            */
            $award->setPhotoName($_FILES["ourAwardPhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($award); 
            
            // Apply changes to database.
            $this->entityManager->flush();
            
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Problem with attached photo.';
        
            return $dataResponse;
        }
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Ok.';
        
        return $dataResponse;

    }
    
    private function decodeJSONdata($data){
        $formFieldsArray = (array)json_decode($data['objArr'])[0];
        return $formFieldsArray;
    }
    
//    private function fileExist($target_file_to_save){
//        
//        // Check if file already exists
//        if (file_exists($target_file_to_save)) {      
//            return true;
//        }
//        
//        return false;
//    }
    
    public function deleteOurAward($id){
        
        //find news with id
        $deleteAward = $this->entityManager->getRepository(OurAwards::class)
                ->find($id);
        
        if($deleteAward == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Award not found. Award can\'t be deleted.';
        
            return $dataResponse;
        }
        
        //initial - prepare the path
        //path to delete photo
        $path_to_delete_photo = './public/upload/awards/';
        
        //target
        $target_file_photo = $path_to_delete_photo . $deleteAward->getPhotoName();
        
        //remove doc if exist
        if(file_exists($target_file_photo)){
            unlink ($target_file_photo);
        }


        //remove from DB
        $this->entityManager->remove($deleteAward);
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Award deleted.';
        
        return $dataResponse;
        
    }
}

