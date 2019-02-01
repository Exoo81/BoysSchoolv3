<?php

namespace Schoollife\Service;

use Schoollife\Entity\SchoolLife;
use User\Entity\User;


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
    
    public function saveSchoolLife($formData){
        
        //create new schoolLife object
        $schoolLife = new SchoolLife();
        
        // json_decode to array
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //set schoolLife fields
        $schoolLife->setStatus(1);
        $schoolLife->setTitle($formFieldsArray['schoolLifeTitle']);
        $schoolLife->setContent($formFieldsArray['schoolLifeContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $schoolLife->setDatePublished($current_date);
        
        
        // set Author
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);

        if($author != null){
            $schoolLife->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($schoolLife);        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        // save file if exist
        if(isset($_FILES['schoolLifePhoto'])){
            
//            $dataResponse['success'] = true;
//            $dataResponse['schoolLifePhoto'] =  $_FILES["schoolLifePhoto"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to save
            $path_to_save_photo = './public/upload/school-life/'.$schoolLife->getId().'/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_photo)) {
                mkdir($path_to_save_photo, 0777, true);
            }
            
            //$target
            $target_file_photo = $path_to_save_photo . basename($_FILES["schoolLifePhoto"]["name"]);
            
            $exif_data = null;
            $iOS_orientation = null;
            if($_FILES["schoolLifePhoto"]['type'] == "image/jpeg"){
                if(function_exists('exif_read_data')){
                    $exif_data = @exif_read_data($_FILES["schoolLifePhoto"]['tmp_name']);
                    $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                }    
            }
                
            // save oryginal photo on server
            if(move_uploaded_file($_FILES["schoolLifePhoto"]["tmp_name"], $target_file_photo)){             
                $this->processImage($target_file_photo, $iOS_orientation);  
            }        

                //return success
                $dataResponse['success'] = true;
//                $dataResponse['Photo name'] =  $_FILES["schoolLifePhoto"]['name'];
//                $dataResponse['Photo type'] =  $_FILES["schoolLifePhoto"]['type'];
//                $dataResponse['Efix_data'] =  $exif_data;
//                $dataResponse['iOS orientation'] =  $iOS_orientation;
//                $dataResponse['photo size'] =  filesize($target_file_photo);
                $dataResponse['responseMsg'] =  'Save news TEST.';
            
            /*
            * Save in db
            */
            $schoolLife->setPhotoName($_FILES["schoolLifePhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($schoolLife); 
            
            // Apply changes to database.
            $this->entityManager->flush();
            
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'School Life saved.';
        
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
//        $editSchoolLife->setStatus($formFieldsArray['schoolLifeStatus']);
        $editSchoolLife->setContent($formFieldsArray['schoolLifeContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $editSchoolLife->setDatePublished($current_date);
        
        // set Author
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);

        if($author != null){
            $editSchoolLife->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
        
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

            
            $exif_data = null;
            $iOS_orientation = null;
            if($_FILES["editSchoolLifePhoto"]['type'] == "image/jpeg"){
                if(function_exists('exif_read_data')){
                    $exif_data = @exif_read_data($_FILES["editSchoolLifePhoto"]['tmp_name']);
                    $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                }    
            }
                
            // save oryginal photo on server
            if(move_uploaded_file($_FILES["editSchoolLifePhoto"]["tmp_name"], $target_file_photo)){             
                $this->processImage($target_file_photo, $iOS_orientation);  
            }        

                //return success
                $dataResponse['success'] = true;
//                $dataResponse['Photo name'] =  $_FILES["editSchoolLifePhoto"]['name'];
//                $dataResponse['Photo type'] =  $_FILES["editSchoolLifePhoto"]['type'];
//                $dataResponse['Efix_data'] =  $exif_data;
//                $dataResponse['iOS orientation'] =  $iOS_orientation;
//                $dataResponse['photo size'] =  filesize($target_file_photo);
                $dataResponse['responseMsg'] =  'Save news TEST.';

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
    
    public function deleteSchoolLife($schoolLifeID){
        
        //find schoolLife
        $schoolLife = $this->entityManager->getRepository(SchoolLife::class)
                        ->find($schoolLifeID);
        
        if($schoolLife == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find this school life.';
            return $dataResponse;
        }
        
        //initial - prepare the path
        $path_to_delete = './public/upload/school-life/'.$schoolLife->getId().'/';
        
        //remove photo
        if($schoolLife->getPhotoName() != null){
            $target_file = $path_to_delete . $schoolLife->getPhotoName();
            if (file_exists($target_file)) {
                unlink ($target_file);
            }
        }
        
        //remove from DB
        $this->entityManager->remove($schoolLife);
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'School Life deleted.';
        
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
    
    private function checkPhotoOrientation($exif_data){
        
        foreach($exif_data as $key => $value){
            if(strtolower($key)== "orientation"){
                return $value;
            }
        }
        
        return 0;
    }
    
    private function checkDegrees($iOS_orientation){
        
        switch ($iOS_orientation):
            case 1:
                return 0;
            case 8:
                return 90;
            case 3:
                return 180;
            case 6:
                return 270;             
        endswitch;
        
    }
    
    private function processImage($target_file_photo, $iOS_orientation){
        
        $image_info = getimagesize($target_file_photo);
        
        $file_size = filesize($target_file_photo); 
        
        if ($image_info['mime'] == 'image/jpeg') {
            
            //shrink photo
            list($width, $heigth)= getimagesize($target_file_photo);
            
            // if Landscape else Portrait
            if($width > $heigth){
                if($width > 1024){
                    $newWidth = 1024;
                    $newHeigth = ($heigth*$newWidth)/$width; 
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
                
            }else{
                if($heigth > 1024){
                    $newHeigth = 1024 ;
                    $newWidth = ($width*$newHeigth)/$heigth;
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
            }
            

            $image = imagecreatefromjpeg($target_file_photo);
            $truecolor = imagecreatetruecolor($newWidth, $newHeigth);
            imagecopyresampled($truecolor, $image, 0, 0, 0, 0, $newWidth, $newHeigth, $width, $heigth);

            if($iOS_orientation > 1){
                $rotateDeg = $this->checkDegrees($iOS_orientation);
                $imageRotate = imagerotate($truecolor, $rotateDeg, 0);
                
//                if($file_size > 500000){                //500000 = 500KB
//                    imagejpeg($imageRotate, $target_file_photo, 70);
//                }else{
//                    imagejpeg($imageRotate, $target_file_photo, 95);
//                }
                imagejpeg($imageRotate, $target_file_photo, 95);
                
                imagedestroy($imageRotate);
            }else{
//                if($file_size > 500000){                //500000 = 500KB
//                    imagejpeg($truecolor, $target_file_photo, 70);
//                }else{
//                    imagejpeg($truecolor, $target_file_photo, 95);
//                }
                imagejpeg($truecolor, $target_file_photo, 95);
            }
            
            imagedestroy($image);
            imagedestroy($truecolor);
        }elseif ($image_info['mime'] == 'image/png') {
            
            //shrink photo
            list($width, $heigth)= getimagesize($target_file_photo);
            
            // if Landscape else Portrait
            if($width > $heigth){
                if($width > 860){
                $newWidth = 860;
                $newHeigth = ($heigth*$newWidth)/$width; 
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
                
            }else{
                if($heigth > 860){
                    $newHeigth = 860 ;
                    $newWidth = ($width*$newHeigth)/$heigth;
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
            }
            
            $image = imagecreatefrompng($target_file_photo);
            $truecolor = imagecreatetruecolor($newWidth, $newHeigth);
            
            imagealphablending($truecolor, false);
            imagesavealpha($truecolor,true);
            $transparent = imagecolorallocatealpha($truecolor, 255, 255, 255, 127);
            imagefilledrectangle($truecolor, 0, 0, $newWidth, $newHeigth, $transparent);
            imagecopyresampled($truecolor, $image, 0, 0, 0, 0, $newWidth, $newHeigth, $width, $heigth);
        
            imagepng($truecolor, $target_file_photo, 6);
            
            imagedestroy($image);
            imagedestroy($truecolor);
        }
    
        
    }
    
}

