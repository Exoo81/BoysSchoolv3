<?php

namespace Application\Model;


use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Newsletter implements InputFilterAwareInterface{
    
    public $id;
    public $title;
    public $published;
    public $file_name;
    
    private $inputFilter;
    private $path_to_save;

    public function exchangeArray(array $data){
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->title  = !empty($data['title']) ? $data['title'] : null;
        $this->published  = !empty($data['published']) ? $data['published'] : null;
        $this->file_name  = !empty($data['file_name']) ? $data['file_name'] : null;
    }
    
     public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
            '%s does not allow injection of an alternate input filter',
            __CLASS__
        ));
    }
    
    public function setPathToFile($path_to_save){
        $this->path_to_save = $path_to_save;
    }

    public function getInputFilter(){
        if ($this->inputFilter) {
            return $this->inputFilter;
        }

        $inputFilter = new InputFilter();

        $inputFilter->add([
            'name' => 'id',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);

        $inputFilter->add([
            'name' => 'title',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        
        // Add validation rules for the "file" field.	 
        $inputFilter->add(([
            'name'     => 'file_name',
            'required' => true,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                    [
                    'name'    => 'FileMimeType',                        
                    'options' => [                            
                            'mimeType'  => ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf']
                             ]
                    ],
            ],
            'filters'  => [                    
                [
                'name' => 'FileRenameUpload',
                'options' => [ 
                            'target'=> $this->path_to_save,
                            'useUploadName'=>true,
                            'useUploadExtension'=>true,
                            'overwrite'=>true,
                            'randomize'=>false
                            ]
                ]
            ],   
        ]));

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
}

