<?php

namespace Parents\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This view helper is used to access to policies list.
 */
class CurrentPolicies extends AbstractHelper {
    
    
    /**
     * Subscription manager.
     * @var Parents\Service\ParentsManager 
     */
    private $parentsManager;
    
    public function __construct($parentsManager) {
        
        $this->parentsManager = $parentsManager;
        
    }
    
    public function __invoke(){
        return $this->parentsManager->getPolicies();
    }
}

