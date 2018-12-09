<?php

namespace Application\Service;

use Application\Entity\Newsletter;



/**
 * This service is responsible for adding/delete newsletter
 * 
 */
class NewsletterManager{
    
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
    
    public function getSeasonNewsletters(){
        
        $newsletters = $this->entityManager->getRepository(Newsletter::class)
                     ->findBySeason($this->currentSeason, ['dateNewsletter'=>'DESC']);
               
        return $newsletters;
    }
    
    public function getSeasonNewslettersWrappedInMonths(){
        
        $newslettersForSeason = $this->getSeasonNewsletters();
        
        if($newslettersForSeason !== null){
            $seasonNewslettersWrappedInMonths = $this->wrappNewsletteresInMonths($newslettersForSeason);
            
            return $seasonNewslettersWrappedInMonths;
        }
        
    }


    public function addNewsletter($formData){
        
        //create new News
        $newsletter = new Newsletter();
        
        // json_decode to array regular fields
        $formFieldsArray = $this->decodeJSONdata($formData);
        
         //save newsletter other data
        $newsletter->setSeason($this->currentSeason);
        $newsletter->setTitle($formFieldsArray['title']);
        
        //$newsletterDate = strtotime($formData['addNewsletterDate']);   //get format m/d/Y
        $dateNewsletter = date('Y-m-d H:i:s', strtotime($formFieldsArray['addNewsletterDate']));
        $newsletter->setDateNewsletter($dateNewsletter);
        
        $current_date = date('Y-m-d H:i:s');
        $newsletter->setDatePublished($current_date);
        
        
        // save doc file if exist
        if(isset($_FILES['addNewsletterDoc'])){
            
            //path to save
            $path_to_save_doc = './public/upload/newsletters/'.$this->currentSeason->getSeasonName().'/';
            
            //check if dir exist else - create
            if(!is_dir($path_to_save_doc)) {
                mkdir($path_to_save_doc, 0777, true);
            }
            
            //$target
            $target_file_doc = $path_to_save_doc . basename($_FILES["addNewsletterDoc"]["name"]);
            
            // Check if file already exists
            // if not exist
            if (!file_exists($target_file_doc)) {
                //save on server
                move_uploaded_file($_FILES["addNewsletterDoc"]["tmp_name"], $target_file_doc);
            }else{
                $dataResponse['success'] = false;
                $dataResponse['responseMsg'] =  'Sorry, this file has already been used in another newsletter. Use a different file or change the name of this file before attaching it.';
        
                return $dataResponse;
            }
            
            // add to entity field
            $newsletter->setDocName($_FILES["addNewsletterDoc"]["name"]);

            
        }else{
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'ERROR: Problem with attached file.';
        
            return $dataResponse;
        }
        
        /*
         * Save in db (file have to be attached
         */
        // Add the entity to the entity manager.
        $this->entityManager->persist($newsletter); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Newsletter saved.';
        
        //TODO
        //send emails to Subscription emails
        
        return $dataResponse;
         
    }
    
    
     public function deleteNewsletter($id){
         
         //find newsletter with id
        $newsletter = $this->entityManager->getRepository(Newsletter::class)
                ->find($id);
        
        if($newsletter == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Newsletter NOT found. Newsletter can\'t be deleted.';
            
            return $dataResponse;
        }
        
        //initial - prepare the path
        //path to delete doc
        $path_to_delete_doc = './public/upload/newsletters/'.$newsletter->getSeason()->getSeasonName().'/';
        
        //target
        $target_file_doc  = $path_to_delete_doc . $newsletter->getDocName();
        
        //remove doc if exist
        if(file_exists($target_file_doc)){
            unlink ($target_file_doc);
        }
        
        //remove from DB
        $this->entityManager->remove($newsletter);
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Deleting newsletter completed.';
        
        return $dataResponse;
        
     }
     
     private function wrappNewsletteresInMonths($newslettersForSeason){
         
         //create list for every month
         $arrayNewslettersInMonth = array();
         
         //list for month
         $arrayMonth = null;
        
         $count = 0;
        $month = null;
        foreach($newslettersForSeason as $newsletter){
             //get newsletter moth
             $newsletterMonth = date("m",strtotime($newsletter->getDateNewsletter()));
             if($newsletterMonth !== $month){

                 // add new month name with new index
                 $count ++;
                 $arrayNewslettersInMonth[$count]['month'] = $this->getNewsletterMonthName($newsletterMonth);

                 // create new list of newsletters for this month
                 $arrayMonth = array();
                 //add this newsletter to $arrayMonth
                 array_push($arrayMonth, $newsletter);
             }else{
                //add this newsletter to current $arrayMonth
                 array_push($arrayMonth, $newsletter);
             }
            
             //set $month as $newsletterMonth
             $month = $newsletterMonth;
             //save current $arrayMonth in $arrayNewslettersInMonth 
                 $arrayNewslettersInMonth[$count]['newsletters'] = $arrayMonth;
             
             
         }
         
         return $arrayNewslettersInMonth;
         
     }
     
     private function getNewsletterMonthName($newsletterMonth){
         
         $monthNameList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
         
         return $monthNameList[$newsletterMonth-1];
     }


     private function decodeJSONdata($data){
        $formFieldsArray = (array)json_decode($data['objArr'])[0];
        return $formFieldsArray;
    }
    
}

