<?php

namespace Classes\Service;

use Classes\Entity\Blog;
use Classes\Entity\ClassBlog;
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
 * This service is responsible for adding/editing etc. blogs in classes
 * 
 */
class ClassesManager{
    
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
    
    public function getBlogsFromSeason(){
        
        
        $blogList = $this->entityManager->getRepository(ClassBlog::class)
                     ->findBySeason($this->currentSeason->getId(), ['level'=>'ASC']);


        if($blogList != null){

            //list of key=level value=array(blogs) 
            $blogListGroupByLevel = $this->getBlogsGroupByLevel($blogList);

            //right-left List
            $rightLeftBlogListGroupByLevel  = $this->getRightLeftBlogsGroupByLevel($blogListGroupByLevel);

                //rightList
                $righBlogListGroupByLevel = $rightLeftBlogListGroupByLevel['right'];
                //leftList
                $leftBlogListGroupByLevel = $rightLeftBlogListGroupByLevel['left'];


            // list of key=level value=color
            $levelColorList = $this->getLevelColorList($blogListGroupByLevel);

            // list of key=level value=title
            $levelTitleList = $this->getLevelTitleList();

            // $fullListsOfBlogs list with $blogListGroupByLevel(key=blogs), $levelColorList(colors) and 
            $fullListsOfBlogs = array();
            $fullListsOfBlogs['blogs-right'] = $righBlogListGroupByLevel;
            $fullListsOfBlogs['blogs-left'] = $leftBlogListGroupByLevel;
            $fullListsOfBlogs['colors'] = $levelColorList;
            $fullListsOfBlogs['title'] = $levelTitleList;
            $fullListsOfBlogs['blogs'] = $blogListGroupByLevel;
            
        }else{
            return null;
        }

        
         
        return $fullListsOfBlogs;
    }
    
    // return blog lists grup by key=level
    private function getBlogsGroupByLevel($blogList) {
        
        
        $blogListGroupByLevel = array();
        
        $blogListLevel_1 = array();
        $blogListLevel_15 = array();
        $blogListLevel_2 = array();
        $blogListLevel_25 = array();
        $blogListLevel_3 = array();
        $blogListLevel_35 = array();
        $blogListLevel_4 = array();
        $blogListLevel_45 = array();
        $blogListLevel_5 = array();
        $blogListLevel_55 = array();
        $blogListLevel_6 = array();
        $blogListLevel_7 = array();
        
        
        foreach ($blogList as $blog){
            $level = $blog->getLevel();
            if($level == 1){
                array_push($blogListLevel_1, $blog);
            }
            if($level == 1.5){
                array_push($blogListLevel_15, $blog);
            }
            if($level == 2){
                array_push($blogListLevel_2, $blog);
            }
            if($level == 2.5){
                array_push($blogListLevel_25, $blog);
            }
            if($level == 3){
                array_push($blogListLevel_3, $blog);
            }
            if($level == 3.5){
                array_push($blogListLevel_35, $blog);
            }
            if($level == 4){
                array_push($blogListLevel_4, $blog);
            }
            if($level == 4.5){
                array_push($blogListLevel_45, $blog);
            }
            if($level == 5){
                array_push($blogListLevel_5, $blog);
            }
            if($level == 5.5){
                array_push($blogListLevel_55, $blog);
            }
            if($level == 6){
                array_push($blogListLevel_6, $blog);
            }
            if($level == 7){
                array_push($blogListLevel_7, $blog);
            }
        }
        
//        //help list with all $blogListAllLevels
//        $blogListAllLevels = narray();
//        
//        array_push($blogListAllLevels, $blogListLevel_1); 
//        array_push($blogListAllLevels, $blogListLevel_15);
//        array_push($blogListAllLevels, $blogListLevel_2);
//        array_push($blogListAllLevels, $blogListLevel_25);
//        array_push($blogListAllLevels, $blogListLevel_3);
//        array_push($blogListAllLevels, $blogListLevel_35);
//        array_push($blogListAllLevels, $blogListLevel_4);
//        array_push($blogListAllLevels, $blogListLevel_45);
//        array_push($blogListAllLevels, $blogListLevel_5);
//        array_push($blogListAllLevels, $blogListLevel_55);
//        array_push($blogListAllLevels, $blogListLevel_6);
//        
        
        
        
        //check if $blogListLevel_1 is empty 
        if (!empty($blogListLevel_1)) {
            //$blogListLevel_1 to $blogListGroupByLevel by key=level
            $key_1 = $blogListLevel_1[0]->getLevel();
            $blogListGroupByLevel[$key_1]  = $blogListLevel_1 ;
        }
        
        //check if $blogListLevel_15 is empty 
        if (!empty($blogListLevel_15)) {
            //$blogListLevel_15 to $blogListGroupByLevel by key=level
            $key15 = $blogListLevel_15[0]->getLevel();
            $blogListGroupByLevel[$key15]  = $blogListLevel_15 ;
        }
        
        //check if $blogListLevel_2 is empty 
        if (!empty($blogListLevel_2)) {
            //$blogListLevel_2 to $blogListGroupByLevel by key=level
            $key2 = $blogListLevel_2[0]->getLevel();
            $blogListGroupByLevel[$key2]  = $blogListLevel_2 ;
        }
        
        //check if $blogListLevel_25 is empty 
        if (!empty($blogListLevel_25)) {
            //$blogListLevel_25 to $blogListGroupByLevel by key=level
            $key25 = $blogListLevel_25[0]->getLevel();
            $blogListGroupByLevel[$key25]  = $blogListLevel_25 ;
        }
        
        //check if $blogListLevel_3 is empty 
        if (!empty($blogListLevel_3)) {
            //$blogListLevel_3 to $blogListGroupByLevel by key=level
            $key3 = $blogListLevel_3[0]->getLevel();
            $blogListGroupByLevel[$key3]  = $blogListLevel_3 ;
        }
        
        //check if $blogListLevel_35 is empty 
        if (!empty($blogListLevel_35)) {
            //$blogListLevel_35 to $blogListGroupByLevel by key=level
            $key35 = $blogListLevel_35[0]->getLevel();
            $blogListGroupByLevel[$key35]  = $blogListLevel_35 ;
        }
        
        //check if $blogListLevel_4 is empty 
        if (!empty($blogListLevel_4)) {
            //$blogListLevel_4 to $blogListGroupByLevel by key=level
            $key4 = $blogListLevel_4[0]->getLevel();
            $blogListGroupByLevel[$key4]  = $blogListLevel_4 ;
        }
        
        //check if $blogListLevel_45 is empty 
        if (!empty($blogListLevel_45)) {
            //$blogListLevel_45 to $blogListGroupByLevel by key=level
            $key45 = $blogListLevel_45[0]->getLevel();
            $blogListGroupByLevel[$key45]  = $blogListLevel_45 ;
        }
        
        //check if $blogListLevel_5 is empty 
        if (!empty($blogListLevel_5)) {
            //$blogListLevel_5 to $blogListGroupByLevel by key=level
            $key5 = $blogListLevel_5[0]->getLevel();
            $blogListGroupByLevel[$key5]  = $blogListLevel_5 ;
        }
        
        //check if $blogListLevel_55 is empty 
        if (!empty($blogListLevel_55)) {
            //$blogListLevel_55 to $blogListGroupByLevel by key=level
            $key55 = $blogListLevel_55[0]->getLevel();
            $blogListGroupByLevel[$key55]  = $blogListLevel_55 ;
        }
        
        //check if $blogListLevel_6 is empty 
        if (!empty($blogListLevel_6)) {
            //$blogListLevel_6 to $blogListGroupByLevel by key=level
            $key6 = $blogListLevel_6[0]->getLevel();
            $blogListGroupByLevel[$key6]  = $blogListLevel_6 ;
        }
        //check if $blogListLevel_7 is empty 
        if (!empty($blogListLevel_7)) {
            //$blogListLevel_7 to $blogListGroupByLevel by key=level
            $key7 = $blogListLevel_7[0]->getLevel();
            $blogListGroupByLevel[$key7]  = $blogListLevel_7 ;
        }
        
        
        
        return $blogListGroupByLevel;
    }
    
    //return array with right and left blogs
    private function getRightLeftBlogsGroupByLevel($blogListGroupByLevel) {
        
        $rightLeftBlogListGroupByLevel = array();
            $rightBlogListGroupByLevel = array();
            $leftBlogListGroupByLevel = array();
        
        $rightCounter = 0;
        $leftCounter = 0;
        foreach ($blogListGroupByLevel as $key => $value){
            if($rightCounter <= $leftCounter){
                $increase = count($value);
                $rightBlogListGroupByLevel[$key] = $value;
                 $rightCounter = $rightCounter + $increase;
            }else{
                $increase = count($value);
                $leftBlogListGroupByLevel[$key] = $value;
                $leftCounter = $leftCounter + $increase;
            }
        }
        
        $rightLeftBlogListGroupByLevel['right'] = $rightBlogListGroupByLevel;
        $rightLeftBlogListGroupByLevel['left'] = $leftBlogListGroupByLevel;
        
        return $rightLeftBlogListGroupByLevel;
    }
    
    // return color for each level
    private function getLevelColorList($blogListGroupByLevel) {
        
        $colorList = ['green', 'red', 'yellow', 'blue', 'orange', 'purpure', 'green','red', 'yellow', 'blue', 'orange', 'purpure'];
        $levelColorList = null;
        $count = 0;
        foreach ($blogListGroupByLevel as $key => $value){
            
            $levelColorList[$key] = $colorList[$count];
            
            $count++;
        }
      
        return $levelColorList;
    }
    
    // return title for each level
    private function getLevelTitleList() {
        
        $titlesList = ['1st Class', '1st - 2nd Class', '2nd Class', '2nd - 3rd Class', '3rd Class', '3rd - 4th Class', '4th Class', '4th - 5th Class', '5th Class', '5th - 6th Class', '6th Class', 'Tír na nÓg - ASD Class'];
        $keysList = ['1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0', '7.0'];
        
        
        
        for($i=0; $i<count($titlesList); $i++){
            $levelTitleList[$keysList[$i]] = $titlesList[$i];
        }
        
      
        return $levelTitleList;
    }
    
//    public function getLevelTitle($blogLevel){
//        $levelTitleList = $this->getLevelTitleList();
//        foreach($levelTitleList as $key => $value){
//            if($key === $blogLevel){
//                return $value;
//            }
//        }
//        return '$levelTitle';
//    }
    
    public function getSelectionForm(){
        
        /*
         * get all users with 'teacher' role
         */
        $teacherList = $this->getAllTeachers();
        
        if($teacherList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'Can not receive data for the select "teacher" option.';
            
            return $dataResponse; 
        }
        
        $selectTeachers = array();
        $selectTeachers[0] = '---select---';
        
        foreach($teacherList as $teacher){
            $selectTeachers[$teacher->getId()] = $teacher->getOurTeamMember()->getFullName();
        }
        
        /*
         * get all users with 'learning support' role
         */
        
        $learningSupportList = $this->getAllLearningSupport();
        
        
        $selectLearningSupport = array();
        $selectLearningSupport[0] = '---select---';
        
        foreach($learningSupportList as $learningSupport){
            $selectLearningSupport[$learningSupport->getId()] = $learningSupport->getOurTeamMember()->getFullName();
        }
        
        
        //get list of levels
        $levelsList = $this->getSelectLevelList();
        if($levelsList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'Can not receive data for the select "class level" option.';
            
            return $dataResponse; 
        }
        
        $dataResponse['classLevel'] = $levelsList;
        $dataResponse['teachers'] = $selectTeachers;
        $dataResponse['learningSupport'] = $selectLearningSupport;
//        $dataResponse['teachersLearningSupport'] = $selectTeacherAndLearningSupportList;
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] = 'Ok.';
        
        return $dataResponse; 
    }
    
    private function getAllTeachers(){
        
        $teacherList = $this->entityManager->getRepository(Teacher::class)
                     ->findByStatus(1);
        
        $asdList = $this->entityManager->getRepository(ASDUnit::class)
                     ->findByStatus(1);
        
        $teachersWithAccountList = array();
        
        if($teacherList === null){
            return $teachersWithAccountList;
        }
        
        //get only with account User
        foreach($teacherList as $teacher){
            if($teacher->getUser() !== null){
                array_push($teachersWithAccountList, $teacher->getUser());
            }
        }
        
        //add ASD to all teachers
        foreach($asdList as $teacher){
            if($teacher->getUser() !== null){
                array_push($teachersWithAccountList, $teacher->getUser());
            }
        }
        
        
        return $teachersWithAccountList;
        
    }
    
    private function getAllLearningSupport(){
        
        $learningSupportList = $this->entityManager->getRepository(LearningSupport::class)
                     ->findByStatus(1);
        
        $learningSupportWithAccountList = array();
        
        if($learningSupportList === null){
            return $learningSupportWithAccountList;
        }
        
        //get only with account User
        foreach($learningSupportList as $learningSupport){
            if($learningSupport->getUser() !== null){
                array_push($learningSupportWithAccountList, $learningSupport->getUser());
            }
        }
        
        return $learningSupportWithAccountList;
    }


    private function getSelectLevelList(){
        
        $titlesList = ['---select---','1st Class', '1st - 2nd Class', '2nd Class', '2nd - 3rd Class', '3rd Class', '3rd - 4th Class', '4th Class', '4th - 5th Class', '5th Class', '5th - 6th Class', '6th Class', 'Tír na nÓg - ASD Class'];
        $keysList = ['0', '1.0', '1.5', '2.0', '2.5', '3.0', '3.5', '4.0', '4.5', '5.0', '5.5', '6.0', '7.0'];
        for($i=0; $i<count($titlesList); $i++){
            $selectLevelList[$keysList[$i]] = $titlesList[$i];
        }

        return $selectLevelList;
    }
    
    public function saveNewBlog($formData){
        
        //create new News
        $classBlog = new ClassBlog();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //save news other data
        $classBlog->setSeason($this->currentSeason);
        $classBlog->setLevel($formFieldsArray['classLevel']);
        
        //find and set teacher by id
        $teacher = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['teacherID']);
        
        if($teacher == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find selected teacher.';
            return $dataResponse;
        }
        
        $classBlog->setTeacher($teacher);
        
        //find and set learning support
        $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['learningSupportID']);
        
        
        $classBlog->setLearningSupport($learningSupport);
        
        //To Fix check if teacher has a blog in this season
//        $blogExist = $this->checkIfBlogExist($teacher, null);
//        
//        if($blogExist){
//            $dataResponse['success'] = false;
//            $dataResponse['responseMsg'] = 'ERROR - This blog already exists.';
//            return $dataResponse;
//        }
        
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($classBlog); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        // save doc file if exist
        if(isset($_FILES['classPhoto'])){
            
//            $dataResponse['success'] = true;
//            $dataResponse['classPhoto'] =  $_FILES["classPhoto"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to save
            $path_to_save_photo = './public/upload/classes/'.$this->currentSeason->getSeasonName().'/';

            //check if dir exist else - create
            if(!is_dir($path_to_save_photo)) {
                mkdir($path_to_save_photo, 0777, true);
            }
            
            //$target
            $target_file_photo = $path_to_save_photo . basename($_FILES["classPhoto"]["name"]);

            
            $exif_data = null;
            $iOS_orientation = null;
            if($_FILES["classPhoto"]['type'] == "image/jpeg"){
                if(function_exists('exif_read_data')){
                    $exif_data = @exif_read_data($_FILES["classPhoto"]['tmp_name']);
                    $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                }    
            }
                
            // save oryginal photo on server
            if(move_uploaded_file($_FILES["classPhoto"]["tmp_name"], $target_file_photo)){             
                $this->processImage($target_file_photo, $iOS_orientation);  
            }        

                //return success
                $dataResponse['success'] = true;
//                $dataResponse['Photo name'] =  $_FILES["classPhoto"]['name'];
//                $dataResponse['Photo type'] =  $_FILES["classPhoto"]['type'];
//                $dataResponse['Efix_data'] =  $exif_data;
//                $dataResponse['iOS orientation'] =  $iOS_orientation;
//                $dataResponse['photo size'] =  filesize($target_file_photo);
                $dataResponse['responseMsg'] =  'Save news TEST.';
            
            /*
            * Save in db
            */
            $classBlog->setPhotoName($_FILES["classPhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($classBlog); 
            
            // Apply changes to database.
            $this->entityManager->flush();
            
        }
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Class blog saved.';
        
        return $dataResponse;
        
    }
    
    private function decodeJSONdata($formData){
        $formFieldsArray = (array)json_decode($formData['objArr'])[0];
        return $formFieldsArray;
    }
    
    private function checkIfBlogExist($teacher, $blogId){
        

        
        
            $queryBuilder = $this->entityManager->createQueryBuilder();


            $queryBuilder->select('blog')
                ->from(ClassBlog::class, 'blog')  
                ->innerJoin('blog.season', 'season')
                ->where('season.id LIKE :seasonID')
                ->andWhere('blog.teacher = :teacher')
                ->setParameter('teacher', $teacher)
                ->setParameter('seasonID', $this->currentSeason->getId());

            $blogsFound = $queryBuilder->getQuery()->getOneOrNullResult();
            
            //add
            if($blogId == null){
                if(!empty($blogsFound)){
                    return true;
                }
            }
            
            //edit
            if($blogId !== null){
                if($blogsFound->getId() !== $blogId){
                    return true;
                } 
            }
            
            
            
            return false;

//            $level_year = $queryBuilder->getQuery()->getResult();
//            //find teacher
//            foreach($level_year as $blog){
//                
//                
//                
//                if($blog->getTeacher()->getId() === $teacher->getId()){
//                    
//                    /*
//                     * if save new blog
//                     */
//                    if($blogId == null){
//                        return true;
//                    }
//                    
//                    /*
//                     * if edit blog
//                     */
//                    if($blog->getId() !== $blogId ){
//                        return true;
//                    }
//                    
//                } 
//                
//                
//                
//            }
//
//            return false;
        
        
        
    }
    
    public function getClassBlogEditData($id){
        
        $classBlog = $this->entityManager->getRepository(Blog::class)
                ->find($id);
        
        if($classBlog === null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - No blog found.';
            
            return $dataResponse;
        }

        /*
         * get all users with 'teacher' role
         */
        $teacherList = $this->getAllTeachers();
        
        if($teacherList == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'Can not receive data for the select "teacher" option.';
            
            return $dataResponse; 
        }
        
        $selectTeacherList = array();
        $selectTeacherList[0] = '---select---';
        
        foreach($teacherList as $teacher){
            $selectTeacherList[$teacher->getId()] = $teacher->getOurTeamMember()->getFullName();
        }

        $teacherID = $classBlog->getTeacher()->getId();
        
        /*
         * get all users with 'learning support' role
         */
        $learningSupportList = $this->getAllLearningSupport();
        
        $selectLearningSupportList = array();
        $selectLearningSupportList[0] = '---select---';
        
        foreach($learningSupportList as $learningSupport){
            $selectLearningSupportList[$learningSupport->getId()] = $learningSupport->getOurTeamMember()->getFullName();
        }
       
        if($classBlog->getLearningSupport() != null){
            $learningSupportID = $classBlog->getLearningSupport()->getId();
        }else{
            $learningSupportID = 0;
        }
        
        /*
         * get list of levels
         */
        $levelsList = $this->getSelectLevelList();
        $currentClassLevel = $classBlog->getLevel();
        


        
        /*
         * get class photo path
         */
        $classPhotoPath = $this->getClassPhotoPath($classBlog->getPhotoName(), $classBlog->getSeason());
        
        $dataResponse['success'] = true;
        $dataResponse['teachers'] = $selectTeacherList;
        $dataResponse['teacherID'] = $teacherID;
        $dataResponse['learningSupport'] = $selectLearningSupportList;
        $dataResponse['learningSupportID'] = $learningSupportID;
        $dataResponse['classLevel'] = $levelsList;
        $dataResponse['currentLevel'] = $currentClassLevel;
        $dataResponse['classPhotoPath'] = $classPhotoPath;
        $dataResponse['responseMsg'] = 'Ok.';
            
        return $dataResponse;
        
        
    }
    
    private function getClassPhotoPath($photoName, $season){
        
        if($photoName === null){
            return null;
        }
   
        //path to edit
            $path_to_edit = 'upload/classes/'.$season->getSeasonName().'/';
            
            $classPhotoPath = $path_to_edit . basename($photoName);
        
        return $classPhotoPath;
    }
    
    public function editClassBlog($formData){
        
        // json_decode to array
        $formFieldsArray = $this->decodeJSONdata($formData);
        
        //find class blog to edit
        $editClassBlog = $this->entityManager->getRepository(ClassBlog::class)
                ->find($formFieldsArray['editClassBlogID']);
        
        if($editClassBlog == null){
            //return error
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Class blog not found. Class blog can\'t be edited.';
        
            return $dataResponse;
        }
        
        /*
         * edit changes from regular fields
         */
        $editClassBlog->setLevel($formFieldsArray['editClassLevel']);
        
        //find and set teacher by id
        $teacher = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['editClassTeacherID']);
        
        if($teacher == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find selected teacher.';
            return $dataResponse;
        }
        $editClassBlog->setTeacher($teacher);
        
        //find and set learning support
        $learningSupport = $this->entityManager->getRepository(User::class)
                ->find($formFieldsArray['editClassLearningSupportID']);
        
        
        $editClassBlog->setLearningSupport($learningSupport);
        
        //To Fix check if blog season+level+teacher exist
//        $blogExist = $this->checkIfBlogExist($teacher, $editClassBlog->getId());
//        
//        if($blogExist){
//            $dataResponse['success'] = false;
//            $dataResponse['responseMsg'] = 'ERROR - This blog already exists.';
//            return $dataResponse;
//        }
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($editClassBlog); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        /*
         *  remove photo if apply
         */
        $dataResponse['removePhoto'] = $formFieldsArray['removePhoto'];
        if($formFieldsArray['removePhoto']){
            
            //path to remove old photo
            $path_to_delete_photo = './public/upload/classes/'.$editClassBlog->getSeason()->getSeasonName().'/';
            
            // photo to delete if exist
            if($editClassBlog->getPhotoName() != null){
                $target_photo_to_delete = $path_to_delete_photo . $editClassBlog->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }
            
            $editClassBlog->setPhotoName(null);
           
            // Add the entity to the entity manager.
            $this->entityManager->persist($editClassBlog); 
            
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        
        // save class photo file if posted by form
        if(isset($_FILES['editClassBlogPhoto'])){ 
//            $dataResponse['success'] = true;
//            $dataResponse['editClassBlogPhoto'] =  $_FILES["editClassBlogPhoto"]['name'];
//            return $dataResponse;

            /*
            * Save on server
            */

            //path to remove/save
            $path_to_photo = './public/upload/classes/'.$editClassBlog->getSeason()->getSeasonName().'/';

            // save new photo form input file field
            //check if dir exist else - create
            if(!is_dir($path_to_photo)) {
                 mkdir($path_to_photo, 0777, true);
            }
            
            //remove old photo if not null (only when input file field choosen without click on remove button "X"
            // photo to delete if exist
            if($editClassBlog->getPhotoName() != null){
                $target_photo_to_delete = $path_to_photo . $editClassBlog->getPhotoName();
                //remove old photo
                // if exist
                if (file_exists($target_photo_to_delete)) {
                    unlink ($target_photo_to_delete);
                }
            }

            //$target new photo
            $target_file_photo = $path_to_photo .$editClassBlog->getId().'-'. basename($_FILES["editClassBlogPhoto"]["name"]);

//            // Check if file already exists
//            // if not exist
//            if (!file_exists($target_file_photo)) {
//                //save on server
//                move_uploaded_file($_FILES["editClassBlogPhoto"]["tmp_name"], $target_file_photo);
//            }
            
            
            $exif_data = null;
            $iOS_orientation = null;
            if($_FILES["editClassBlogPhoto"]['type'] == "image/jpeg"){
                if(function_exists('exif_read_data')){
                    $exif_data = @exif_read_data($_FILES["editClassBlogPhoto"]['tmp_name']);
                    $iOS_orientation = $this->checkPhotoOrientation($exif_data);
                }    
            }
                
            // save oryginal photo on server
            if(move_uploaded_file($_FILES["editClassBlogPhoto"]["tmp_name"], $target_file_photo)){             
                $this->processImage($target_file_photo, $iOS_orientation);  
            }        

                //return success
                $dataResponse['success'] = true;
                $dataResponse['Photo name'] =  $_FILES["editClassBlogPhoto"]['name'];
                $dataResponse['Photo type'] =  $_FILES["editClassBlogPhoto"]['type'];
                $dataResponse['Efix_data'] =  $exif_data;
                $dataResponse['iOS orientation'] =  $iOS_orientation;
                $dataResponse['photo size'] =  filesize($target_file_photo);
                $dataResponse['responseMsg'] =  'Save news TEST.';
            
            

            /*
            * Save in db
            */
            $editClassBlog->setPhotoName($editClassBlog->getId().'-'.$_FILES["editClassBlogPhoto"]["name"]);

            // Add the entity to the entity manager.
            $this->entityManager->persist($editClassBlog); 
            
            // Apply changes to database.
            $this->entityManager->flush();

        }
        
        

        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Edit class blog completed.';
        
        return $dataResponse;
        
    }
    
    public function deleteClassBlog($blogID){
        
         //find news with id
        $deleteBlog = $this->entityManager->getRepository(ClassBlog::class)
                ->find($blogID);
        
        if($deleteBlog == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Class blog NOT found. Class blog can\'t be deleted.';
            
            return $dataResponse;
        }
        
        //check if blog is empty
        $blogEmpty = $this->checkIfBlogIsEmpty($deleteBlog);
        
        if(!$blogEmpty){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR - The blog can not be deleted (blog contains posts).';
            
            return $dataResponse;
        }
        
        //initial - prepare the path
        //path to delete photo
        $path_to_delete_photo = './public/upload/classes/'.$deleteBlog->getSeason()->getSeasonName().'/';
              
            //remove photo if exist
            if($deleteBlog->getPhotoName() != null){
                unlink ($path_to_delete_photo . $deleteBlog->getPhotoName());
            }
            
            //remove from DB
            $this->entityManager->remove($deleteBlog);
            $this->entityManager->flush();

            
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Deleting class blog completed.';
        
        return $dataResponse;
        
    }
    
    private function checkIfBlogIsEmpty($deleteBlog){
        
        $posts = $this->entityManager->getRepository(Post::class)
                     ->findByBlog($deleteBlog);
        
        if($posts == null){
            return true;
        }
        
        return false;
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

