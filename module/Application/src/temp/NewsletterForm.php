<?php

namespace Application\Form;

use Zend\Form\Form;

class NewsletterForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('newsletter-form');

        $this->add([
            'name' => 'id',
            'type' => 'hidden',
        ]);
        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title',
            ],
        ]);
       
        $this->add([
            'type'  => 'Zend\Form\Element\File',
            'name' => 'file_name',
            'attributes' => [                
                'id' => 'file_name',
            ],
            'options' => [
                'label' => 'Upload file',
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

