<?php

namespace User\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\ArrayInput;
use User\Validator\UserExistsValidator;

/**
 * This form is used to collect user's email, full name, password and status. The form 
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */

class UserForm extends Form{
    
    /**
     * Scenario ('create' or 'update').
     * @var string 
     */
    private $scenario;
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager = null;
    
    /**
     * Current user (to edit).
     * @var User\Entity\User 
     */
    private $user = null;
    
    /**
     * Constructor.     
     */
    public function __construct($scenario = 'create', $entityManager = null, $user = null){
        
        // Define form name
        parent::__construct('user-form');
        
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;
        
        $this->addElements();
        $this->addInputFilter(); 
    }
    
     protected function addElements() {
         
        // Add "title" field
        if ($this->scenario == 'create') {
            $this->add([            
                'type'  => 'select',
                'name' => 'title',            
                'options' => [
                    'label' => 'Title',
                    'label_attributes' => [
                        'class'  => 'form-control'
                     ], 
                    'value_options' => [
                        1 => 'Mr.',
                        2 => 'Mrs.', 
                        3 => 'Ms.',
                    ],
                ],
                'attributes' => [
                    'hidden' => true,
                ],       
            ]);
        }else{
            $this->add([            
                'type'  => 'select',
                'name' => 'title',            
                'options' => [
                    'label' => 'Title',
                    'label_attributes' => [
                        'class'  => 'form-control'
                     ], 
                    'value_options' => [
                        1 => 'Mr.',
                        2 => 'Mrs.', 
                        3 => 'Ms.',
                    ],
                ],    
            ]);
        }

        // Add "first_name" field
        $this->add([            
            'type'  => 'text',
            'name' => 'first_name',            
            'options' => [
                'label' => 'First Name',
                'label_attributes' => [
                        'class'  => 'form-control'
                ], 
            ],
            'attributes' => [
                'hidden' => true,
            ],
        ]);
        
        // Add "last_name" field
        if ($this->scenario == 'create') {
            $this->add([            
                'type'  => 'text',
                'name' => 'last_name',            
                'options' => [
                    'label' => 'Last Name',
                    'label_attributes' => [
                            'class'  => 'form-control'
                    ], 
                ],
                'attributes' => [
                    'hidden' => true,
                ],
            ]);
        }else{
            $this->add([            
            'type'  => 'text',
            'name' => 'last_name',            
            'options' => [
                'label' => 'Last Name',
                'label_attributes' => [
                        'class'  => 'form-control'
                ], 
            ],
        ]);
        }
        
        // Add "status" field
        $this->add([            
            'type'  => 'select',
            'name' => 'status',
            'options' => [
                'label' => 'Account status',
                'label_attributes' => [
                        'class'  => 'form-control'
                ], 
                'value_options' => [
                    1 => 'Active',
                    2 => 'Retired',                    
                ]
            ],
            'attributes' => [
                'hidden' => true,
            ],
        ]);
        
        
        // Add "email" field
        $this->add([            
            'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'E-mail',
                'label_attributes' => [
                        'class'  => 'form-control'
                     ], 
            ],
        ]);
        
        if ($this->scenario == 'create') {
        
            // Add "password" field
            $this->add([            
                'type'  => 'password',
                'name' => 'password',
                'options' => [
                    'label' => 'Password',
                    'label_attributes' => [
                        'class'  => 'form-control'
                     ],  
                ],
            ]);
            
            // Add "confirm_password" field
            $this->add([            
                'type'  => 'password',
                'name' => 'confirm_password',
                'options' => [
                    'label' => 'Confirm password',
                    'label_attributes' => [
                        'class'  => 'form-control'
                     ], 
                ],
            ]);
        }
        

             
        // Add "roles" field
        $this->add([            
            'type'  => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'roles',
            'options' => [
                'label' => 'Please, choose the roles in the SYSTEM',
                'label_attributes' => [
                        'class'  => 'form-control'
                     ], 
            ],          
        ]);
        
        if ($this->scenario == 'update_parents_assoc') {
            $this->add([            
                'type'  => 'select',
                'name' => 'parents_assoc_roles',
                'options' => [
                    'label' => 'Please, choose the roles in the PARENTS ASSOCIATION',
                    'label_attributes' => [
                            'class'  => 'form-control'
                    ],
                    'empty_option' => 'Please choose some role',
                    'value_options' => [
                        1 => 'Chairperson',
                        2 => 'Vice Chairperson',  
                        3 => 'Secretary',
                        4 => 'Treasurer',
                    ]
                ],
            ]);
            
        }
        
        
        
        if ($this->scenario == 'create') {
            // Add the Submit button
            $this->add([
                'type'  => 'submit',
                'name' => 'submit',
                'attributes' => [                
                    'value' => 'Add'
                ],
            ]);
        }else{
            // Add the Submit button
            $this->add([
                'type'  => 'submit',
                'name' => 'submit',
                'attributes' => [                
                    'value' => 'Edit'
                ],
            ]);
        }
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() {
        
        // Create main input filter
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        // Add input for "email" field
        $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck'    => false,                            
                        ],
                    ],
                    [
                        'name' => UserExistsValidator::class,
                        'options' => [
                            'entityManager' => $this->entityManager,
                            'user' => $this->user
                        ],
                    ],                    
                ],
            ]);
        
        // Add input for "title" field
        $inputFilter->add([
                'name'     => 'title',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'ToInt'],
                ],                
                'validators' => [
                    ['name'=>'InArray', 'options'=>['haystack'=>[1, 2, 3]]]
                ],
            ]); 
        
        // Add input for "first_name" field
        $inputFilter->add([
                'name'     => 'first_name',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        
        // Add input for "last_name" field
        $inputFilter->add([
                'name'     => 'last_name',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
        
        if ($this->scenario == 'create') {
            
            // Add input for "password" field
            $inputFilter->add([
                    'name'     => 'password',
                    'required' => true,
                    'filters'  => [                        
                    ],                
                    'validators' => [
                        [
                            'name'    => 'StringLength',
                            'options' => [
                                'min' => 6,
                                'max' => 64
                            ],
                        ],
                    ],
                ]);
            
            // Add input for "confirm_password" field
            $inputFilter->add([
                    'name'     => 'confirm_password',
                    'required' => true,
                    'filters'  => [                        
                    ],                
                    'validators' => [
                        [
                            'name'    => 'Identical',
                            'options' => [
                                'token' => 'password',                            
                            ],
                        ],
                    ],
                ]);
        }
            
        // Add input for "status" field
        $inputFilter->add([
                'name'     => 'status',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'ToInt'],
                ],                
                'validators' => [
                    ['name'=>'InArray', 'options'=>['haystack'=>[1, 2]]]
                ],
            ]);
        
        // Add input for "update_parents_assoc" field
        if ($this->scenario == 'update_parents_assoc') {
            $inputFilter->add([
                'name'     => 'parents_assoc_roles',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'ToInt'],
                ],                
                'validators' => [
                    ['name'=>'InArray', 'options'=>['haystack'=>[1, 2, 3, 4]]]
                ],
            ]);
        }
        
        // Add input for "roles" field
        $inputFilter->add([
                'class'    => ArrayInput::class,
                'name'     => 'roles',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'ToInt'],
                ],                
                'validators' => [
                    ['name'=>'GreaterThan', 'options'=>['min'=>0]]
                ],
            ]); 
        
        
    }
    
    
}