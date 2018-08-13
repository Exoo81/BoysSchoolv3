<?php


namespace Application\Form;

use Zend\Form\Form;

class WelcomeMsgForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('welcomeMsg-form');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'content',
            'type' => 'textarea',
            'options' => [
                'label' => 'Welcome message',
                'rows' => '30',
            ],
        ]);
        
        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Go',
                'id'    => 'submitbutton',
            ],
        ]);
    }
}

