<?php

namespace Contact\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This view helper is used to access to contact.
 */
class CurrentContact extends AbstractHelper {
    
    
    /**
     * Subscription manager.
     * @var Contact\Service\ContactManager 
     */
    private $contactManager;
    
    public function __construct($contactManager) {
        
        $this->contactManager = $contactManager;
        
    }
    
    public function __invoke(){
        return $this->contactManager->getContactInfo();
    }
}

