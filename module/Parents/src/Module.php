<?php

namespace Parents;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface{
    
    
    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig(){
        return include __DIR__ . '/../config/module.config.php';
    }
    
}

