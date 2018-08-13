<?php

namespace Application\Form;

use Zend\Form\Form;

class AboutUsForm extends Form
{
    public function __construct($name = null)
    {
        // We will ignore the name provided to the constructor
        parent::__construct('aboutUs-form');

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
            'name' => 'content',
            'type' => 'textarea',
            'options' => [
                'label' => 'About us - content',
            ],
        ]);
        $this->add([
            'name' => 'principal_name',
            'type' => 'text',
            'options' => [
                'label' => 'Principal (name)',
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

