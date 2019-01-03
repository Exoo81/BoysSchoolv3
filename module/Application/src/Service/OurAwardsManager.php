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
//            if (!file_exists($target_file_photo)) {
//                //save on server
//                move_uploaded_file($_FILES["ourAwardPhoto"]["tmp_name"], $target_file_photo);
//            }
            
            // Check if file already exists
            // if not exist  
                $exif_data = null;
                $iOS_orientation = null;
                if($_FILES["ourAwardPhoto"]['type'] == "image/jpeg"){
                    if(function_exists('exif_read_data')){
                        $exif_data = @exif_read_data($_FILES["ourAwardPhoto"]['tmp_name']);
                        $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                    }
                }
                
                
                // save oryginal photo on server
                if(move_uploaded_file($_FILES["ourAwardPhoto"]["tmp_name"], $target_file_photo)){             
                    $this->processImage($target_file_photo, $iOS_orientation);  
                }        

                //return success
                $dataResponse['success'] = true;
//                $dataResponse['Photo name'] =  $_FILES["ourAwardPhoto"]['name'];
//                $dataResponse['Photo type'] =  $_FILES["ourAwardPhoto"]['type'];
//                $dataResponse['Efix_data'] =  $exif_data;
//                $dataResponse['iOS orientation'] =  $iOS_orientation;
//                $dataResponse['photo size'] =  filesize($target_file_photo);
                $dataResponse['responseMsg'] =  'Save news TEST.';
              

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
    
    
    private function checkPhotoOrientation($exif_data){
        
        //
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
                if($width > 480){
                $newWidth = 480;
                $newHeigth = ($heigth*$newWidth)/$width; 
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
                
            }else{
                if($heigth > 480){
                    $newHeigth = 480 ;
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
                
                if($file_size > 100000){                //500000 = 500KB
                    imagejpeg($imageRotate, $target_file_photo, 70);
                }else{
                    imagejpeg($imageRotate, $target_file_photo, 90);
                }
                imagedestroy($imageRotate);
            }else{
                if($file_size > 100000){                //500000 = 500KB
                    imagejpeg($truecolor, $target_file_photo, 70);
                }else{
                    imagejpeg($truecolor, $target_file_photo, 90);
                }
            }
            
            imagedestroy($image);
            imagedestroy($truecolor);
        } elseif ($image_info['mime'] == 'image/png') {
            
            //shrink photo
            list($width, $heigth)= getimagesize($target_file_photo);
            
            // if Landscape else Portrait
            if($width > $heigth){
                if($width > 480){
                $newWidth = 480;
                $newHeigth = ($heigth*$newWidth)/$width; 
                }else{
                    $newWidth = $width;
                    $newHeigth = $heigth;
                }
                
            }else{
                if($heigth > 480){
                    $newHeigth = 480 ;
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
            
            
            //imagecopyresampled($truecolor, $image, 0, 0, 0, 0, $newWidth, $newHeigth, $width, $heigth);
            
            imagepng($truecolor, $target_file_photo, 6);
            
            imagedestroy($image);
            imagedestroy($truecolor);
        }

    }
}

