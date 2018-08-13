<?php

namespace Application\Service;

use Application\Entity\AboutUs;



/**
 * This service is responsible for editing "about us"
 * 
 */
class AboutUsManager{
    
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
    
    public function getEditAboutUs($id){
        
        //find About Us message with id
        $editAboutUs = $this->entityManager->getRepository(AboutUs::class)
                ->find($id);
        
        if($editAboutUs == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  '"About us" information not found.';
            
            return $dataResponse;
        }
        
       
        // convert onejct News to JSON
        $aboutUsJSON = $editAboutUs->jsonSerialize();
        $dataResponse['aboutUs'] = $aboutUsJSON; 
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Found a "About us" to edit.';
        
        return $dataResponse;
    }
    
    public function editAboutUs($data){
        
        
        
        //find About Us message with id
        $editAboutUs = $this->entityManager->getRepository(AboutUs::class)
                ->find($data['id']);
        
        if($editAboutUs == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  '"About us" information not found.';
            
            return $dataResponse;
        }
        
        //update AboutUs object
        $editAboutUs->setTitle($data['title']);
        $editAboutUs->setContent($data['content']);
        $editAboutUs->setPrincipalName($data['principalName']);
            
        // Add the entity to the entity manager.
        $this->entityManager->persist($editAboutUs);
        
        // Apply changes to database.
        $this->entityManager->flush();
            
        $aboutUsJSON = $editAboutUs->jsonSerialize();
        $dataResponse['aboutUs'] = $aboutUsJSON; 
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  '"About us" information edited.';
        
        return $dataResponse;
    }
    
}

