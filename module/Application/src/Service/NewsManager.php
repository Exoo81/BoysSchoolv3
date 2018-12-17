<?php

namespace Application\Service;

use Application\Entity\NewsPost;
use Application\Entity\NewsBlog;
use Classes\Entity\Post;
use User\Entity\User;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;



/**
 * This service is responsible for adding/editing etc. news
 * pagination.
 */
class NewsManager{
    
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
    
    
    
    
    
    public function getCurrentNewsBlog(){
        
        $newsBlog = $this->entityManager->getRepository(NewsBlog::class)
                     ->findOneBySeason($this->currentSeason);
        
        return $newsBlog;
    }
    
    public function fetchAll($paginated=false){
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
        
        return $this->entityManager->getRepository(News::class)
                     ->findBy(['datePublished'=>'DESC']);
    }
    
    private function fetchPaginatedResults(){
        
        
        // Get all news as query
        $news = $this->getAllNewsQuery();

        $adapter = new DoctrineAdapter(new ORMPaginator($news, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(4);        
        
        
        return $paginator;
    }
    
    private function getAllNewsQuery(){
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
       
            $queryBuilder->select('post')
                ->from(Post::class, 'post')
                ->innerJoin('post.season', 'season')                    
                ->andWhere('season.id = :seasonID')
                ->setParameter('seasonID', $this->currentSeason->getId())
                ->orderBy('post.datePublished', 'DESC');
           
            return $queryBuilder->getQuery();
        
        
    }
    
    public function saveNews($formData){
        
        //create new News
        $news = new NewsPost();
 
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //find current season news Blog
        $blog = $this->entityManager->getRepository(NewsBlog::class)
                ->find($formFieldsArray['blogID']);
        
        if($blog != null){
            $news->setBlog($blog);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Gallery blog id. Blog not exist. Try again.';
        
            return $dataResponse;
        }
        
        //save news other data
        $news->setSeason($this->currentSeason);
        $news->setTitle($formFieldsArray['newsTitle']);
        $news->setContent($formFieldsArray['newsContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $news->setDatePublished($current_date);
        
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);
        
        if($author == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find author.';
            return $dataResponse;
        }
        
        $news->setAuthor($author);
        
              
        // Add the entity to the entity manager.
        $this->entityManager->persist($news); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        // save doc file if exist
        if(isset($_FILES['newsDoc'])){
            
//            $dataResponse['success'] = true;
//            $dataResponse['newsDoc'] =  $_FILES["newsDoc"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to save
            $path_to_save_doc = './public/upload/school-news/'.$this->currentSeason->getSeasonName().'/'.$news->getId().'/doc/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }

            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["newsDoc"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["newsDoc"]["tmp_name"], $target_file_doc);
            }

            /*
            * Save in db
            */
            $news->setDocName($_FILES["newsDoc"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($news); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        // save photo file if exist
        if(isset($_FILES['newsPhoto'])){
            
//            $dataResponse['success'] = true;
//            $dataResponse['newsPhoto'] =  $_FILES["newsPhoto"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to save
            $path_to_save_photo = './public/upload/school-news/'.$this->currentSeason->getSeasonName().'/'.$news->getId().'/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_photo)) {
                mkdir($path_to_save_photo, 0777, true);
            }

            //$target
            $target_file_photo = $path_to_save_photo . basename($_FILES["newsPhoto"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_photo)) {
                
                
                $exif_data = null;
                $iOS_orientation = null;
                if($_FILES["newsPhoto"]['type'] == "image/jpeg"){
                  $photoTempName = $_FILES["newsPhoto"]['tmp_name'];
                  $exif_data = exif_read_data($photoTempName);
                  $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                }
                
                
                // save oryginal photo on server
                if(move_uploaded_file($_FILES["newsPhoto"]["tmp_name"], $target_file_photo)){             
                    $this->compressImage($target_file_photo, $target_file_photo, $iOS_orientation);  
                }        

//                //return success
//                $dataResponse['success'] = true;
//                $dataResponse['Photo name'] =  $_FILES["newsPhoto"]['name'];
//                $dataResponse['Photo type'] =  $_FILES["newsPhoto"]['type'];
//                $dataResponse['Efix_data'] =  $exif_data;
//                $dataResponse['iOS orientation'] =  $iOS_orientation;
//                $dataResponse['photo size'] =  filesize($target_file_photo);
//                $dataResponse['responseMsg'] =  'Save news TEST.';
              
            }

            /*
            * Save in db
            */
            $news->setPhotoName($_FILES["newsPhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($news); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'News saved.';
        
        return $dataResponse;
        

    }
    
    public function getEditedNews($newsID){
        
        // Find a news with such ID.
        $news = $this->entityManager->getRepository(NewsPost::class)
                ->find($newsID);
        
        if($news !== null){
            $newsJSON = $news->jsonSerialize();
            $dataResponse['newsToEdit'] = $newsJSON;
 
            //build picure(s) path 
            $path_to_photo = 'upload/school-news/'.$news->getSeason()->getSeasonName().'/'.$news->getId().'/'.$news->getPhotoName();
            $dataResponse['photoPath'] = $path_to_photo;

     
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t edit news.';
            return $dataResponse;
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'News added.';
        
        return $dataResponse;
    }
    
    public function editNews($dataToEdit){
        
        
        // json_decode to array
        $formFieldsArray = $this->decodeJSONdata($dataToEdit);
        
        //find news to edit
        $editNews = $this->entityManager->getRepository(NewsPost::class)
                ->find($formFieldsArray['editNewsID']);
        
        if($editNews == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: News not found. News can\'t be edited.';
        
            return $dataResponse;
        }
        
        /*
         * edit changes from regular fields
         */
        $editNews->setTitle($formFieldsArray['editNewsTitle']);
        $editNews->setContent($formFieldsArray['editNewsContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $editNews->setDatePublished($current_date);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($editNews); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        /*
         *  remove doc if apply
         */
        if($formFieldsArray['removeDoc']){
            
            //path to remove old document
            $path_to_delete_doc = './public/upload/school-news/'.$editNews->getSeason()->getSeasonName().'/'.$editNews->getId().'/doc/';
            
            // document to delete if exist
            if($editNews->getDocName() != null){
                $target_doc_to_delete = $path_to_delete_doc . $editNews->getDocName();
                //remove old photo
                // if exist
                if (file_exists($target_doc_to_delete)) {
                    unlink ($target_doc_to_delete);
                }
            }
            
            $editNews->setDocName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($editNews); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        /*
         *  remove photo if apply
         */
        if($formFieldsArray['removePhoto']){
            
            //path to remove old photo
            $path_to_delete_photo = './public/upload/school-news/'.$editNews->getSeason()->getSeasonName().'/'.$editNews->getId().'/';
            
            // photo to delete if exist
            if($editNews->getPhotoName() != null){
                $target_photo_to_delete = $path_to_delete_photo . $editNews->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }
            
            $editNews->setPhotoName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($editNews); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        
        // save doc file if posted by form
        if(isset($_FILES['editNewsDoc'])){ 
//            $dataResponse['success'] = true;
//            $dataResponse['editNewsDoc'] =  $_FILES["editNewsDoc"]['name'];

            /*
            * Save on server
            */

            //path to save
            $path_to_save_doc = './public/upload/school-news/'.$editNews->getSeason()->getSeasonName().'/'.$editNews->getId().'/doc/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                 mkdir($path_to_save_doc, 0777, true);
            }

            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["editNewsDoc"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["editNewsDoc"]["tmp_name"], $target_file_doc);
            }

            /*
            * Save in db
            */
            $editNews->setDocName($_FILES["editNewsDoc"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($editNews); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        // save photo if posted by form
        if(isset($_FILES['editNewsPhoto'])){ 
//            $dataResponse['success'] = true;
//            $dataResponse['editNewsPhoto'] =  $_FILES["editNewsPhoto"]['name'];

            /*
            * Save on server
            */

            //path to remove/save
            $path_to_photo = './public/upload/school-news/'.$editNews->getSeason()->getSeasonName().'/'.$editNews->getId().'/';

            //check if dir exist else - create
            if(!is_dir($path_to_photo)) {
                 mkdir($path_to_photo, 0777, true);
            }
            
            //remove old photo if not null (only when input file field choosen without click on remove button "X"
            // photo to delete if exist
            if($editNews->getPhotoName() != null){
                $target_photo_to_delete = $path_to_photo . $editNews->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }
            

            //$target new photo
            $target_photo_to_save = $path_to_photo . basename($_FILES["editNewsPhoto"]["name"]);

            // Check if file already exists
            // if not exist
            if (!file_exists($target_photo_to_save)) {
                //save on server
                move_uploaded_file($_FILES["editNewsPhoto"]["tmp_name"], $target_photo_to_save);
            }

            /*
            * Save in db
            */
            $editNews->setPhotoName($_FILES["editNewsPhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($editNews); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Edit news completed.';
        
        return $dataResponse;
        
    }
    
    
    private function decodeJSONdata($data){
        $formFieldsArray = (array)json_decode($data['objArr'])[0];
        return $formFieldsArray;
    }
    
    public function deleteNews($id){
        
        
        //find news with id
        $deleteNews = $this->entityManager->getRepository(NewsPost::class)
                ->find($id);
        
        if($deleteNews == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'News NOT found. News can\'t be deleted.';
            
            return $dataResponse;
        }
        
        //initial - prepare the path
        //path to delete doc
        $path_to_delete_doc = './public/upload/school-news/'.$deleteNews->getSeason()->getSeasonName().'/'.$deleteNews->getId().'/doc/';
        //path to delete photo
        $path_to_delete_photo = './public/upload/school-news/'.$deleteNews->getSeason()->getSeasonName().'/'.$deleteNews->getId().'/';
              
            //remove doc if exist
            if($deleteNews->getDocName() != null){
                unlink ($path_to_delete_doc . $deleteNews->getDocName());
            }
            
            //remove photo if exist
            if($deleteNews->getPhotoName() != null){
                unlink ($path_to_delete_photo . $deleteNews->getPhotoName());
            }
            
            //remove from DB
            $this->entityManager->remove($deleteNews);
            $this->entityManager->flush();

            
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Deleting news completed.';
        
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
    
    private function compressImage($target_file_photo, $image_destination, $iOS_orientation){
        
        $image_info = getimagesize($target_file_photo);
        
        $file_size = filesize($target_file_photo); 
        
        if ($image_info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($target_file_photo);

            if($iOS_orientation > 1){
                $rotateDeg = $this->checkDegrees($iOS_orientation);
                $imageRotate = imagerotate($image, $rotateDeg, 0);
                
                if($file_size > 600000){                //600000 = 600KB
                    imagejpeg($imageRotate, $image_destination, 50);
                }else{
                    imagejpeg($imageRotate, $image_destination, 100);
                }
//                        imagedestroy($imageSource);
//                        imagedestroy($image);
            }else{
                if($file_size > 600000){                //600000 = 600KB
                    imagejpeg($image, $image_destination, 50);
                }
            }
            
            imagedestroy($image);
        } elseif ($image_info['mime'] == 'image/png') {
            $image = imagecreatefrompng($target_file_photo);
            imagepng($image, $image_destination, 6);
            
            imagedestroy($image);
        }
    
        return $image_destination;
        
    }

}

