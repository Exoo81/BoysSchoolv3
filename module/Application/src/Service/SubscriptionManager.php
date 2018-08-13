<?php

namespace Application\Service;

use Application\Entity\Subscription;



/**
 * This service is responsible for adding/delete subscription
 * 
 */
class SubscriptionManager{
    
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
    
    public function addSubscription($email){
        
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Subscription added.';
        $dataResponse['email'] = $email;
        
        //find Subscription with email
        $subscription = $this->entityManager->getRepository(Subscription::class)
                ->findOneByEmail($email);
        
        if($subscription != null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] =  'Subscription exist.';
            
        }else{
            //save subscription
            $subscription = new Subscription();
            
            $subscription->setEmail($email);
            
            //save in DB
            // Add the entity to the entity manager.
            $this->entityManager->persist($subscription);
        
            // Apply changes to database.
            $this->entityManager->flush();
        }
        
        return $dataResponse;
    }
    
    public function deleteSubscription($email){
        
        //not implemented yet
        
    }
    
}

