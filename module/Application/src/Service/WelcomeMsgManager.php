<?php

namespace Application\Service;

use Application\Entity\WelcomeMsg;



/**
 * This service is responsible for editing welcome message
 * 
 */
class WelcomeMsgManager{
    
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
    
    public function getEditWelcomeMsg($id){
        
        
        //find Welcom message with id
        $welcomeMsg = $this->entityManager->getRepository(WelcomeMsg::class)
                ->find($id);
        
        if($welcomeMsg == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Welcome message not found.';
            
            return $dataResponse;
        }
        
        // convert onejct News to JSON
        $welcomeMsgJSON = $welcomeMsg->jsonSerialize();
        $dataResponse['welcomeMsg'] = $welcomeMsgJSON;
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Found a "Welcome message" to edit.';
        
        return $dataResponse;
    }
    
    public function editWelcomeMsg($data){
        
        
        //find welcome message to edit
        $editWelcomeMsg = $this->entityManager->getRepository(WelcomeMsg::class)
                ->find($data['id']);
        
        if($editWelcomeMsg != null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Welcome message not found.';
            
        }
        //save changes
        $editWelcomeMsg->setContent($data['welcomeMsg']);
            
        // Add the entity to the entity manager.
        $this->entityManager->persist($editWelcomeMsg);
        
        // Apply changes to database.
        $this->entityManager->flush();
            
        $dataResponse['welcomeMsg'] =  $editWelcomeMsg->jsonSerialize();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  '"Welcome message" to edited.';
        
        return $dataResponse;
    }
    
    
    
}

