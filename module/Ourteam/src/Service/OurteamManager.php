<?php

namespace Ourteam\Service;

use Ourteam\Entity\Principal;
use Ourteam\Entity\Management;
use Ourteam\Entity\Teacher;
use Ourteam\Entity\LearningSupport;
use Ourteam\Entity\SNA;
use Ourteam\Entity\ASDUnit;
use Ourteam\Entity\Secretary;
use Ourteam\Entity\Caretaker;
use Ourteam\Entity\OurTeam;

use User\Entity\User;
use User\Entity\Season;




/**
 * This service is responsible for adding/editing elements on Ourteam page 
 * 
 */
class OurteamManager{
    
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
    
    public function getPrincipal(){
        $memberList = $this->entityManager->getRepository(Principal::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
    }
    
    public function getTeachers(){
        $memberList = $this->entityManager->getRepository(Teacher::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getBoardOfManagement(){
        $memberList = $this->entityManager->getRepository(Management::class)
                     ->findBy(['status'=>OurTeam::STATUS_ACTIVE],['managementRole'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getLearningSupport(){
        $memberList = $this->entityManager->getRepository(LearningSupport::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getSNA(){
        $memberList = $this->entityManager->getRepository(SNA::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getAsdUnit(){
        $memberList = $this->entityManager->getRepository(ASDUnit::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getSecretary(){
        $memberList = $this->entityManager->getRepository(Secretary::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function getCaretaker(){
        $memberList = $this->entityManager->getRepository(Caretaker::class)
                     ->findBy([],['status'=>'ASC']);
        
        return $memberList;
        
    }
    
    public function addOurTeamMamber($formData){

        switch ($formData['memberType']) {
        case 'principal':
            $member = new Principal();
            break;
        case 'teacher':
            $member = new Teacher();
            break;
        case 'learning_support':
            $member = new LearningSupport();
            break;
        case 'sna':
            $member = new SNA();
            break;
        case 'asd_unit':
            $member = new ASDUnit();
            break;
        case 'secretary':
            $member = new Secretary();
            break;
        case 'caretaker':
            $member = new Caretaker();
            break;
        }
        
        $member->setTitle($formData['memberTitle']);
        $member->setFirstName($formData['memberFirstName']);
        $member->setLastName($formData['memberLastName']);
        $member->setStatus(1);
        
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member (Our Team) added.';
        $dataResponse['newMemberID'] =  $member->getId();
        $dataResponse['newMemberFullName'] = $member->getFullName();
        
        return $dataResponse;
    }
    
    public function addBoardOfManagementMamber($formData){
        
        $member = new Management();
        
        $member->setTitle($formData['memberTitle']);
        $member->setManagementRole($formData['boardMemberRole']);
        $member->setFirstName($formData['memberFirstName']);
        $member->setLastName($formData['memberLastName']);
        $member->setStatus(1);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member (Board of Management) added.';
        
        return $dataResponse;
    }

        
    
    
    public function deleteOurTeamMamber($memberID){
        
        //get member with ID
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($memberID);
        
        if($member == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find member of our team to delete.';
            return $dataResponse;
        }
        
        //get User (account) if exist
        if($member->getUser() !== null){
            $user = $this->entityManager->getRepository(User::class)
                    ->find($member->getUser());
            
            if($user !== null){
                $user->setStatus(2);
                // Add the entity to the entity manager.
                $this->entityManager->persist($user); 
                // Apply changes to database.
                $this->entityManager->flush();
            }
        }
        
        
        
        $member->setStatus(2);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member (Our Team) deleted.';
        
        return $dataResponse;

    }
    
    
    public function activateOurTeamMamber($memberID){
        
        //get member with ID
        $member = $this->entityManager->getRepository(OurTeam::class)
                ->find($memberID);
        
        if($member == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find member of our team to activate.';
            return $dataResponse;
        }
        
        //get User (account) if exist
        if($member->getUser() !== null){
            $user = $this->entityManager->getRepository(User::class)
                    ->find($member->getUser());
            
            if($user !== null){
                $user->setStatus(1);
                // Add the entity to the entity manager.
                $this->entityManager->persist($user); 
                // Apply changes to database.
                $this->entityManager->flush();
            }
        }
        
        $member->setStatus(1);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member (Our Team) activate.';
        
        return $dataResponse;

    }
    
    public function deleteBoardOfManagementMamber($memberID){
        
        //get member with ID
        $member = $this->entityManager->getRepository(Management::class)
                ->find($memberID);
        
        if($member == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t find member of Board to delete.';
            return $dataResponse;
        }
        
        $member->setStatus(2);
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($member); 
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Member (Board of Management) deleted.';
        
        return $dataResponse;
        
    }
    
}

