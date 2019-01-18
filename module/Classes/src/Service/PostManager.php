<?php

namespace Classes\Service;

use Classes\Entity\Post;
use Classes\Entity\ClassPost;
use Classes\Entity\Blog;
use Classes\Entity\ClassBlog;
use Classes\Entity\PostImage;
use User\Entity\User;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;



/**
 * This service is responsible for adding/editing etc. posts in blog
 * 
 */
class PostManager{
    
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
    
    public function getAllPostsForBlog($blogID, $paginated=false){
        
        if ($paginated) {
            return $this->fetchPaginatedResults($blogID);
        }
        
        return $this->entityManager->getRepository(Post::class)
                      ->findBy(array('blog' => $blogID), array('datePublished' => 'DESC'));
        
    }
    
    private function fetchPaginatedResults($blogID){
        
        // Get all post as query
        $posts = $this->getAllPostQuery($blogID);
        
        if($posts->getResult() == null){
            return null;
        }
        
//        foreach($posts as $post){
//            
//            //find all pic witch id post
//            $gallery = $this->entityManager->getRepository(PostImage::class)
//                ->findBy(array('post' => $post->getId()));
//                
//            $post->addPostGallery($gallery);
//            
//        }
        
        $adapter = new DoctrineAdapter(new ORMPaginator($posts, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(6);
        return $paginator;
    }
    
    private function getAllPostQuery($blogID){
        
     
        
        $queryBuilder = $this->entityManager->createQueryBuilder();
    
        $queryBuilder->select('post')
            ->from(ClassPost::class, 'post')
            ->innerJoin('post.blog', 'blog')
            ->where('blog.id = :blogID')
//                ->andWhere('post.postGallery is not empty')
            ->setParameter('blogID', $blogID)
            ->orderBy('post.datePublished', 'DESC');
        
       
        return $queryBuilder->getQuery();

    }
    
    public function saveNewPost($formData){
        
        //create new Post
        $post = new ClassPost();

        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        

        //get Blog from formData
        $blog = $this->entityManager->getRepository(ClassBlog::class)
                ->find($formFieldsArray['blogID']);
        
        if($blog != null){
            $post->setBlog($blog);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Blog id. Blog not exist. Try again.';
        
            return $dataResponse;
        }
        
        
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['authorID']);
        
        if($author != null){
            $post->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
  
        //save new post other data
        $post->setSeason($this->currentSeason);
        $post->setTitle($formFieldsArray['postTitle']);
        $post->setContent($formFieldsArray['postContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $post->setDatePublished($current_date);
        
              
        // Add the entity to the entity manager.
        $this->entityManager->persist($post); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
    
        
        //save photo(s) posted
        if(!empty($_FILES)) {
 
            $count = 0;
            foreach($_FILES as $file){
                
                // save doc file if exist
                if(isset($_FILES['addPostDoc'])){ 
                    $dataResponse['success'] = true;
                    $dataResponse['addPostDoc'] =  $_FILES["addPostDoc"]['name'];
////                    return $dataResponse;

                        /*
                         * Save on server
                         */

                        //path to save
                        $path_to_save_doc = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/doc/';

                        //check if dir exist else - create
                        if(!is_dir($path_to_save_doc)) {
                            mkdir($path_to_save_doc, 0777, true);
                        }

                        //$target
                        $target_file_doc = $path_to_save_doc . basename($_FILES["addPostDoc"]["name"]);

                        // Check if file already exists
                        // if not exist
                        if (!file_exists($target_file_doc)) {
                            //save on server
                            move_uploaded_file($_FILES["addPostDoc"]["tmp_name"], $target_file_doc);
                        }

                        /*
                         * Save in db
                         */
                        $post->setDocName($_FILES["addPostDoc"]["name"]);

                        // Add the entity to the entity manager.
                        $this->entityManager->persist($post); 
            
                        // Apply changes to database.
                        $this->entityManager->flush();

                }
                
                if(isset($_FILES['addPostVideo'])){
                    $dataResponse['success'] = true;
                    $dataResponse['addPostVideo'] =  $_FILES["addPostVideo"]['name'];
                    
                    /*
                    * Save on server
                    */

                    //path to save
                    $path_to_save_video = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save_video)) {
                        mkdir($path_to_save_video, 0777, true);
                    }
                    
                    //$target
                    $target_file_video = $path_to_save_video . basename($_FILES["addPostVideo"]["name"]);
                    
                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file_video)) {
                        //save on server
                        move_uploaded_file($_FILES["addPostVideo"]["tmp_name"], $target_file_video);
                    }
                    
                    /*
                    * Save in db
                    */
                    $post->setVideoName($_FILES["addPostVideo"]["name"]);

                    // Add the entity to the entity manager.
                    $this->entityManager->persist($post); 
            
                    // Apply changes to database.
                    $this->entityManager->flush();
                    
                }
                
                if(isset($_FILES['file'.$count])){
                    
                    $dataResponse['success'] = true;
                    $dataResponse['photo'.$count] =  $_FILES['file'.$count]['name'];
                    /*
                     * Save on server
                     */

                    //path to save
                    $path_to_save = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save)) {
                        mkdir($path_to_save, 0777, true);
                    }

                    
                    $target_file = $path_to_save . basename($_FILES["file".$count]["name"]);

//                    // Check if file already exists
//                    // if not exist
//                    if (!file_exists($target_file)) {
//                        //save on server
//                        move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file);
//                    }
                    
                    $exif_data = null;
                    $iOS_orientation = null;
                    if($_FILES["file".$count]['type'] == "image/jpeg"){
                        if(function_exists('exif_read_data')){
                            $exif_data = @exif_read_data($_FILES["file".$count]['tmp_name']);
                            $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                        }    
                    }

                    // save oryginal photo on server
                    if(move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file)){             
                        $this->processImage($target_file, $iOS_orientation);  
                    }        

                    //return success
                    $dataResponse['success'] = true;
                    $dataResponse['Photo name'] =  $_FILES["file".$count]['name'];
                    $dataResponse['Photo type'] =  $_FILES["file".$count]['type'];
                    $dataResponse['Efix_data'] =  $exif_data;
                    $dataResponse['iOS orientation'] =  $iOS_orientation;
                    $dataResponse['photo size'] =  filesize($target_file);
                    $dataResponse['responseMsg'] =  'Save news TEST.';

                    /*
                     * Save in db
                     */
                    $postImage = new PostImage();
                    $postImage->setPhotoName($_FILES["file".$count]["name"]);
                    $postImage->setPost($post);

                    // Add the entity to the entity manager.
                    $this->entityManager->persist($postImage); 
            
                    // Apply changes to database.
                    $this->entityManager->flush();
                    
                    $count ++;
                }

            }
    
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Post saved.';
        
        return $dataResponse;
        
        
    }
    
    private function decodeJSONdata($formData){
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        return $formFieldsArray;
    }


    public function getEditedPost($postID){
        
        // Find a post with such ID.
        $post = $this->entityManager->getRepository(ClassPost::class)
                ->find($postID);
        
        if($post !== null){
            $postJSON = $post->jsonSerialize();
            $dataResponse['postToEdit'] = $postJSON;

                
                //build picure(s) path 
                $path_to_read = 'upload/classes/'.$post->getBlog()->getSeason()->getSeasonName().'/'.$post->getBlog()->getId().'/'.$post->getId().'/';
                
                //find all pictures for this post
                $queryBuilder = $this->entityManager->createQueryBuilder();
            
                $queryBuilder->select('i')
                    ->from(PostImage::class, 'i')
                    ->where('i.post = :post' )
                        ->setParameter('post', $post);
                $query = $queryBuilder->getQuery()->getArrayResult();
            
                $pictureList = array();
                
                foreach($query as $postImage){
                    $target_path_photo = $path_to_read . basename($postImage['photoName']);
                    //array_push($pictureList, $target_path_photo);
                    $pictureList[$postImage['id']] = $target_path_photo;
                   
                }
                $dataResponse['pictureList'] = $pictureList;
     
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get post to edit.';
            return $dataResponse;
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Post to edit - ready.';
        
        return $dataResponse;
        
    }
    
    public function editPost($formData){
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        // json_decode to array picture to remove
        $picture_to_remove_list = $this->decodeJSONPostImageToRemove($formData);
        
        // find post to edit
        $post  = $this->entityManager->getRepository(ClassPost::class)
                        ->find(intval($formFieldsArray['editPostID']));
        if($post == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find post to edit.';
            return $dataResponse;
        }
        
        //find post's blog
        $blog = $post->getBlog();

        if($blog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find post\'s blog.'.$blog->getId();
            return $dataResponse;
        }
        
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['editPostAuthorID']);
        
        if($author != null){
            $post->setAuthor($author);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Author id. User not exist. Try again.';
        
            return $dataResponse;
        }
        
        /*
         * edit changes from regular fields
         */
        $post->setTitle($formFieldsArray['editPostTitle']);
        $post->setContent($formFieldsArray['editPostContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $post->setDatePublished($current_date);
        
              
        // Add the entity to the entity manager.
        $this->entityManager->persist($post); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        /*
         * remove selected photos
         */
        foreach($picture_to_remove_list as $postImageID){

            //get PostImage
            $postImageToDelete = $this->entityManager->getRepository(PostImage::class)
                ->find(intval($postImageID));
       
            
            if($postImageToDelete == null){
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find image.';
                return $dataResponse;
            
            }
   
            // build path for delere photo   
            $path_to_delete = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';
                
            // file to delete if exist
            if($postImageToDelete->getPhotoName() != null){
                $target_photo_to_delete = $path_to_delete . $postImageToDelete->getPhotoName();
                //remove old photo
                unlink ($target_photo_to_delete);
            }
            
            $this->entityManager->remove($postImageToDelete);
            $this->entityManager->flush();

        }
        
        
        /*
         *  remove doc if apply
         */
        if($formFieldsArray['removeDoc']){
            
            //path to remove old document
            $path_to_delete_doc = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/doc/';
            
            // document to delete if exist
            if($post->getDocName() != null){
                $target_doc_to_delete = $path_to_delete_doc . $post->getDocName();
                //remove old photo
                // if exist
                if (file_exists($target_doc_to_delete)) {
                    unlink ($target_doc_to_delete);
                }
            }
            
            $post->setDocName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($post); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        /*
         *  remove video if apply
         */
        if($formFieldsArray['removeVideo']){
            
            //path to remove old video
            $path_to_delete_video = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';
            
            // video to delete if exist
            if($post->getVideoName() != null){
                $target_video_to_delete = $path_to_delete_video . $post->getVideoName();
                //remove old photo
                // if exist
                if (file_exists($target_video_to_delete)) {
                    unlink ($target_video_to_delete);
                }
            }
            
            $post->setVideoName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($post); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        //save photo(s) posted
        if(!empty($_FILES)) {
 
            $count = 0;
            foreach($_FILES as $file){
                
                // save doc file if exist
                if(isset($_FILES['editPostDoc'])){ 
//                    $dataResponse['success'] = true;
//                    $dataResponse['editPostDoc'] =  $_FILES["editPostDoc"]['name'];

                        /*
                         * Save on server
                         */

                        //path to save
                        $path_to_save_doc = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/doc/';

                        //check if dir exist else - create
                        if(!is_dir($path_to_save_doc)) {
                            mkdir($path_to_save_doc, 0777, true);
                        }

                        //$target
                        $target_file_doc = $path_to_save_doc . basename($_FILES["editPostDoc"]["name"]);

                        // Check if file already exists
                        // if not exist
                        if (!file_exists($target_file_doc)) {
                            //save on server
                            move_uploaded_file($_FILES["editPostDoc"]["tmp_name"], $target_file_doc);
                        }

                        /*
                         * Save in db
                         */
                        $post->setDocName($_FILES["editPostDoc"]["name"]);

                        // Add the entity to the entity manager.
                        $this->entityManager->persist($post); 
            
                        // Apply changes to database.
                        $this->entityManager->flush();

                }
                
                if(isset($_FILES['editPostVideo'])){
//                    $dataResponse['success'] = true;
//                    $dataResponse['editPostVideo'] =  $_FILES["editPostVideo"]['name'];
                    
                    /*
                    * Save on server
                    */

                    //path to save
                    $path_to_save_video = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save_video)) {
                        mkdir($path_to_save_video, 0777, true);
                    }
                    
                    //$target
                    $target_file_video = $path_to_save_video . basename($_FILES["editPostVideo"]["name"]);
                    
                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file_video)) {
                        //save on server
                        move_uploaded_file($_FILES["editPostVideo"]["tmp_name"], $target_file_video);
                    }
                    
                    /*
                    * Save in db
                    */
                    $post->setVideoName($_FILES["editPostVideo"]["name"]);

                    // Add the entity to the entity manager.
                    $this->entityManager->persist($post); 
            
                    // Apply changes to database.
                    $this->entityManager->flush();
                    
                }
                
                if(isset($_FILES['file'.$count])){
                    
//                    $dataResponse['success'] = true;
//                    $dataResponse['photo'.$count] =  $_FILES['file'.$count]['name'];
                    /*
                     * Save on server
                     */

                    //path to save
                    $path_to_save = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save)) {
                        mkdir($path_to_save, 0777, true);
                    }
                   
                    $target_file = $path_to_save . basename($_FILES["file".$count]["name"]);

//                    // Check if file already exists
//                    // if not exist
//                    if (!file_exists($target_file)) {
//                        //save on server
//                        move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file);
//                    }
                    
                                        $exif_data = null;
                    $iOS_orientation = null;
                    if($_FILES["file".$count]['type'] == "image/jpeg"){
                        if(function_exists('exif_read_data')){
                            $exif_data = @exif_read_data($_FILES["file".$count]['tmp_name']);
                            $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                        }    
                    }

                    // save oryginal photo on server
                    if(move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file)){             
                        $this->processImage($target_file, $iOS_orientation);  
                    }        

                    //return success
                    $dataResponse['success'] = true;
                    $dataResponse['Photo name'] =  $_FILES["file".$count]['name'];
                    $dataResponse['Photo type'] =  $_FILES["file".$count]['type'];
                    $dataResponse['Efix_data'] =  $exif_data;
                    $dataResponse['iOS orientation'] =  $iOS_orientation;
                    $dataResponse['photo size'] =  filesize($target_file);
                    $dataResponse['responseMsg'] =  'Save news TEST.';

                    /*
                     * Save in db
                     */
                    $postImage = new PostImage();
                    $postImage->setPhotoName($_FILES["file".$count]["name"]);
                    $postImage->setPost($post);

                    // Add the entity to the entity manager.
                    $this->entityManager->persist($postImage); 
            
                    // Apply changes to database.
                    $this->entityManager->flush();
                    
                    $count ++;
                }

            }
    
        }
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Post edited.';
        
        return $dataResponse;
        
    }
    
    private function decodeJSONPostImageToRemove($formData){
        $size = sizeof((array)json_decode($formData['picture_to_remove']));
        $picture_to_remove_list = array();
        if($size > 0){
            for($i=0; $i<$size; $i++){
                array_push($picture_to_remove_list, (int)json_decode($formData['picture_to_remove'])[$i]);
            }
        }
        
        //$picture_to_remove_list = (array)json_decode($formData['picture_to_remove'])[0];
        return $picture_to_remove_list;
    }
    
    public function deletePost($postID){
        
        //find post with id
        $post = $this->entityManager->getRepository(ClassPost::class)
                ->find($postID);
        
        if($post == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find post to delete.';
            return $dataResponse;
        }
        
        $blog = $post->getBlog();
        
        if($blog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find post\'s blog .';
            return $dataResponse;
        }
        
        //initial - prepare the path
        $path_to_delete = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';
             
        
        
        //remove document
        if($post->getDocName() != null){
            $path_to_delete_doc = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/doc/';
            $target_doc = $path_to_delete_doc . $post->getDocName();
            if (file_exists($target_doc)) {
                unlink ($target_doc);
            }
        }
        
        //remove video
        if($post->getVideoName() != null){
            $path_to_delete_video = './public/upload/classes/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';
            $target_video = $path_to_delete_video . $post->getVideoName();
            if (file_exists($target_video)) {
                unlink ($target_video);
            }
        }
        
        $photosList = $post->getPostGallery();

        //remove gallery
        foreach($photosList as $photo){
            //remove files if exist
            if($photo->getPhotoName() != null){
                $target_photo = $path_to_delete . $photo->getPhotoName();
                if (file_exists($target_photo)) {
                    //remove file
                    unlink ($target_photo);
                }
                //remove $postImage from db
                $this->entityManager->remove($photo);
                $this->entityManager->flush();
            }
        }
            
        //remove from DB
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Post deleted.';
        
        return $dataResponse;
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
    
    private function processImage($target_file, $iOS_orientation){
        
        $image_info = getimagesize($target_file);
        
        $file_size = filesize($target_file); 
        
        if ($image_info['mime'] == 'image/jpeg') {
            
            //shrink photo
            list($width, $heigth)= getimagesize($target_file);
            
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
            

            $image = imagecreatefromjpeg($target_file);
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
                imagejpeg($imageRotate, $target_file, 95);
                
                imagedestroy($imageRotate);
            }else{
//                if($file_size > 500000){                //500000 = 500KB
//                    imagejpeg($truecolor, $target_file_photo, 70);
//                }else{
//                    imagejpeg($truecolor, $target_file_photo, 95);
//                }
                imagejpeg($truecolor, $target_file, 95);
            }
            
            imagedestroy($image);
            imagedestroy($truecolor);
        }elseif ($image_info['mime'] == 'image/png') {
            
            //shrink photo
            list($width, $heigth)= getimagesize($target_file);
            
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
            
            $image = imagecreatefrompng($target_file);
            $truecolor = imagecreatetruecolor($newWidth, $newHeigth);
            
            imagealphablending($truecolor, false);
            imagesavealpha($truecolor,true);
            $transparent = imagecolorallocatealpha($truecolor, 255, 255, 255, 127);
            imagefilledrectangle($truecolor, 0, 0, $newWidth, $newHeigth, $transparent);
            imagecopyresampled($truecolor, $image, 0, 0, 0, 0, $newWidth, $newHeigth, $width, $heigth);
            

            imagepng($truecolor, $target_file, 6);
            
            imagedestroy($image);
            imagedestroy($truecolor);
        }
    
        
    }
    
}

