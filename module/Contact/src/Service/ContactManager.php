<?php

namespace Contact\Service;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail as SendmailTransport;
use Contact\Entity\Contact;
use User\Entity\User;


/**
 * This service is responsible for adding/editing elements on Contact page 
 * 
 */
class ContactManager{
    
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
    
    public function getContactInfo(){
        
        $contact = $this->entityManager->getRepository(Contact::class)
                     ->find(1);
        
        
        return $contact;
    }
    
    public function getContactInformationToEdit(){
        
        //get contact information with id 1
        $contact = $this->entityManager->getRepository(Contact::class)
                     ->find(1);
        
        if($contact == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get contact information to edit.';
            return $dataResponse;
        }
        
        $contactJSON = $contact->jsonSerialize();
        $dataResponse['contactToEdit'] = $contactJSON;
        
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Contact information found.';
        
        return $dataResponse;
    }
    
    public function editContactInformation($formData){
        
        //get contact information with id 1
        $contact = $this->entityManager->getRepository(Contact::class)
                     ->find(1);
        
        if($contact == null){
            $dataResponse['success'] = false;
            $dataResponse['responseMsg'] = 'ERROR - We couldn\'t get contact information to edit.';
            return $dataResponse;
        }
        
        //set new data
        $contact->setSchoolName($formData['contactSchoolName']);
        $contact->setAddress($formData['contactAddress']);
        $contact->setEmail($formData['contactEmail']);
        $contact->setPhoneMain($formData['contactPhoneMain']);
        $contact->setWwwUrl($formData['contactWwwUrl']);
        
        if($formData['contactPhoneAlt1'] == 0){
            $contact->setPhoneAlt1(null);
        }else{
            $contact->setPhoneAlt1($formData['contactPhoneAlt1']);
        }
        
        if($formData['contactPhoneAlt2'] == 0){
            $contact->setPhoneAlt2(null);
        }else{
            $contact->setPhoneAlt2($formData['contactPhoneAlt2']);
        }
        
        
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($contact); 
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Contact information edited.';
        
        return $dataResponse;
    }
    
    
    public function sendMessage($formData){
        
        //get data from form
        $emailFrom = $formData['email'];
        $author = $formData['name'];
        $title = $formData['title'];
        $contentMsg = $formData['message'];
        
        $message = new Message();
        
        $message->addFrom("info@oranmoreboysns.ie", "CONTACT FORM - Oranmore National Boys School Website")
                ->addTo("marcin.piskor@gmail.com") 
                ->setSubject($title);
        
//        $message->setBody($contentMsg);
        $htmlPart = new \Zend\Mime\Part('<html><body><h6><i>The message was sent from the contact form at www.oranmoreboysns.ie</i></h6><h4>'.$contentMsg.'</h4><h4>Regards,</h4><h4>'.$author.'</h4></body></html>');
        $htmlPart->type = "text/html";
        
        $textPart = new \Zend\Mime\Part($contentMsg);
        $textPart->type = "text/plain";
        
        $body = new \Zend\Mime\Message();
        $body->setParts(array($textPart, $htmlPart));
        
        $message->setBody($body);
        
        $message->addReplyTo($emailFrom, $author);
        
        $transport = new SendmailTransport();
        $transport->send($message);
        
        //TODO
        //send emails to Subscription emails
        
        
        
        $dataResponse['author'] = $author;
        
        //return success
        $dataResponse['success'] = true;
        $dataResponse['responseMsg'] =  'Message was sent.';
        
        return $dataResponse;
        
    }
    
    
}

