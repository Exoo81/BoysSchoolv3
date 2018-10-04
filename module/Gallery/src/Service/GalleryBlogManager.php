<?php

namespace Gallery\Service;

use Gallery\Entity\GalleryBlog;
use Gallery\Entity\GalleryPost;
use Classes\Entity\PostImage;
use Classes\Entity\Post;
use User\Entity\User;
use User\Entity\Season;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Zend\Paginator\Paginator;
use Classes\Entity\Blog;



/**
 * This service is responsible for adding/editing etc. gallery blogs 
 * 
 */
class GalleryBlogManager{
    
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
    
    public function getCurrentGalleryBlog(){
        
        $galleryBlog = $this->entityManager->getRepository(GalleryBlog::class)
                     ->findOneBySeason($this->currentSeason);
        
        return $galleryBlog;
    }
    
    public function getGalleriesFromSeason($paginated=false) {
        
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
        
        //return $this->entityManager->getRepository(GalleryPost::class)
         //             ->findBy(array('blog' => $blogID), array('datePublished' => 'DESC'));
    }
    
    private function fetchPaginatedResults(){
        
        // Get all post as query
        $galleryPostList = $this->getAllPostQuery();
        
        if($galleryPostList->getResult() == null){
            return null;
        }
        
     
//        foreach($galleryPostList as $post){
//            
//            if($post->getType() === 'CLASS_POST'){
//                //find all pic witch id post
//                $gallery = $this->entityManager->getRepository(PostImage::class)
//                    ->findBy(array('post' => $post->getId()));
//                
//                if($gallery !=null){
//                    $post->addPostGallery($gallery);
//                }else{
//                    $this->entityManager->detach($post[0]);
//                    
//                }    
//            }
//
//        }
        
        
        
        $adapter = new DoctrineAdapter(new ORMPaginator($galleryPostList, false));
        $paginator = new Paginator($adapter);

        $paginator->setDefaultItemCountPerPage(6);
        
        return $paginator;
    }
    
    private function getAllPostQuery(){
        
       $queryBuilder = $this->entityManager->createQueryBuilder();
       
            $queryBuilder->select('post')
                ->from(GalleryPost::class, 'post')
                  ->innerJoin('post.season', 'season')  
                
                ->andWhere('post.postGallery is not empty')
                ->orWhere('post.videoName IS NOT NULL')
                    
                ->andWhere('season.id = :seasonID')
                ->setParameter('seasonID', $this->currentSeason->getId())

                ->orderBy('post.datePublished', 'DESC');

            
            return $queryBuilder->getQuery();
        
    }
    
    // return color list
    public function getColorList() {
        
        $colorList = ['red', 'orange', 'green', 'blue', 'red', 'purpure', 'green','red', 'yellow', 'blue', 'orange'];

        return $colorList;
    }
    
    public function checkIfGallery(){
        
        if(!empty($_FILES)) {
            return true;
        }

        return false;
    }
    
    public function saveNewGalleryPost($formData){
        
        //create new Post
        $post = new GalleryPost();

        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        

        //get Blog from formData
        $blog = $this->entityManager->getRepository(GalleryBlog::class)
                ->find($formFieldsArray['blogID']);
        
        if($blog != null){
            $post->setBlog($blog);
        }else{
            //return error info
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Error Gallery blog id. Blog not exist. Try again.';
        
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
        $post->setTitle($formFieldsArray['galleryTitle']);
        $post->setContent($formFieldsArray['galleryContent']);
        
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
                
                
                if(isset($_FILES['addGalleryVideo'])){
//                    $dataResponse['success'] = true;
//                    $dataResponse['addGalleryVideo'] =  $_FILES["addGalleryVideo"]['name'];
                    
                    /*
                    * Save on server
                    */

                    //path to save
                    $path_to_save_video = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save_video)) {
                        mkdir($path_to_save_video, 0777, true);
                    }
                    
                    //$target
                    $target_file_video = $path_to_save_video . basename($_FILES["addGalleryVideo"]["name"]);
                    
                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file_video)) {
                        //save on server
                        move_uploaded_file($_FILES["addGalleryVideo"]["tmp_name"], $target_file_video);
                    }
                    
                    /*
                    * Save in db
                    */
                    $post->setVideoName($_FILES["addGalleryVideo"]["name"]);

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
                    $path_to_save = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save)) {
                        mkdir($path_to_save, 0777, true);
                    }

                    
                    $target_file = $path_to_save . basename($_FILES["file".$count]["name"]);

                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file)) {
                        //save on server
                        move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file);
                    }

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
        $dataResponse['responseMsg'] =  'Gallery saved.';
        
        return $dataResponse;
    }
    
    private function decodeJSONdata($formData){
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        return $formFieldsArray;
    }
    
    public function getGalleryToEdit($galleryID){
        
        // Find a gallery post with such ID.
        $post = $this->entityManager->getRepository(GalleryPost::class)
                ->find($galleryID);
        
        if($post !== null){
            $postJSON = $post->jsonSerialize();
            $dataResponse['postToEdit'] = $postJSON;

                
                //build picure(s) path 
                $path_to_read = 'upload/gallery/'.$post->getBlog()->getSeason()->getSeasonName().'/'.$post->getBlog()->getId().'/'.$post->getId().'/';
                
                //find all pictures for this post
                $queryBuilder = $this->entityManager->createQueryBuilder();
            
                $queryBuilder->select('i')
                    ->from(PostImage::class, 'i')
                    ->where('i.post = :post' )
                        ->setParameter('post', $post);
                $query = $queryBuilder->getQuery()->getArrayResult();
            
                $pictureList = array();
                
                foreach($query as $galleryPostImage){
                    $target_path_photo = $path_to_read . basename($galleryPostImage['photoName']);
                    //array_push($pictureList, $target_path_photo);
                    $pictureList[$galleryPostImage['id']] = $target_path_photo;
                   
                }
                $dataResponse['pictureList'] = $pictureList;
     
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get gallery to edit.';
            return $dataResponse;
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Gallery to edit - ready.';
        
        return $dataResponse;
        
    }
    
    public function editGalleryPost($formData){
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        // json_decode to array picture to remove
        $picture_to_remove_list = $this->decodeJSONPostImageToRemove($formData);
        
        // find galleryPost to edit
        $post  = $this->entityManager->getRepository(GalleryPost::class)
                        ->find(intval($formFieldsArray['editGalleryID']));
        if($post == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find gallery to edit.';
            return $dataResponse;
        }
        
        //find post's blog
        $blog = $post->getBlog();

        if($blog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We could not find a blog for the gallery.'.$blog->getId();
            return $dataResponse;
        }
        
        //get author form formData
        $author = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['editAuthorID']);
        
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
        $post->setTitle($formFieldsArray['editGalleryTitle']);
        $post->setContent($formFieldsArray['editGalleryContent']);
        
        $current_date = date('Y-m-d H:i:s');
        $post->setDatePublished($current_date);
        
              
        // Add the entity to the entity manager.
        $this->entityManager->persist($post); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        /*
         * remove selected photos
         */
        foreach($picture_to_remove_list as $galleryPostImageID){

            //get GalleryPostImage
            $galleryPostImageToDelete = $this->entityManager->getRepository(PostImage::class)
                ->find(intval($galleryPostImageID));
       
            
            if($galleryPostImageToDelete == null){
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find gallery image to delete.';
                return $dataResponse;
            
            }
   
            // build path for delere photo   
            $path_to_delete = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';
                
            // file to delete if exist
            if($galleryPostImageToDelete->getPhotoName() != null){
                $target_photo_to_delete = $path_to_delete . $galleryPostImageToDelete->getPhotoName();
                //remove old photo
                unlink ($target_photo_to_delete);
            }
            
            $this->entityManager->remove($galleryPostImageToDelete);
            $this->entityManager->flush();

        }

        /*
         *  remove video if apply
         */
        if($formFieldsArray['removeVideo']){
            
            //path to remove old video
            $path_to_delete_video = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';
            
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

                if(isset($_FILES['editGalleryVideo'])){
//                    $dataResponse['success'] = true;
//                    $dataResponse['editGalleryVideo'] =  $_FILES["editGalleryVideo"]['name'];
                    
                    /*
                    * Save on server
                    */

                    //path to save
                    $path_to_save_video = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save_video)) {
                        mkdir($path_to_save_video, 0777, true);
                    }
                    
                    //$target
                    $target_file_video = $path_to_save_video . basename($_FILES["editGalleryVideo"]["name"]);
                    
                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file_video)) {
                        //save on server
                        move_uploaded_file($_FILES["editGalleryVideo"]["tmp_name"], $target_file_video);
                    }
                    
                    /*
                    * Save in db
                    */
                    $post->setVideoName($_FILES["editGalleryVideo"]["name"]);

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
                    $path_to_save = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';

                    //check if dir exist else - create
                    if(!is_dir($path_to_save)) {
                        mkdir($path_to_save, 0777, true);
                    }

                    
                    $target_file = $path_to_save . basename($_FILES["file".$count]["name"]);

                    // Check if file already exists
                    // if not exist
                    if (!file_exists($target_file)) {
                        //save on server
                        move_uploaded_file($_FILES["file".$count]["tmp_name"], $target_file);
                    }

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
        $dataResponse['responseMsg'] =  'Gallery edited.';
        
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
    
    public function deleteGalleryPost($galleryID){
        
        //find galleryPost with id
        $post = $this->entityManager->getRepository(GalleryPost::class)
                ->find($galleryID);
        
        if($post == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find gallery post to delete.';
            return $dataResponse;
        }
        
        //find post's blog
        $blog = $post->getBlog();
//        $blog = null;
        if($blog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We could not find a blog for the gallery.';
            return $dataResponse;
        }
        
        //initial - prepare the path
        $path_to_delete = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/';

        
        //remove video
        if($post->getVideoName() != null){
            $path_to_delete_video = './public/upload/gallery/'.$blog->getSeason()->getSeasonName().'/'.$blog->getId().'/'.$post->getId().'/video/';
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
                //remove $galleryPostImage from db
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
    
    
    
}

