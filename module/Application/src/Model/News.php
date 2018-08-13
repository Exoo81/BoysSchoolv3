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

class News implements InputFilterAwareInterface{
    public $id;
    public $title;
    public $content;
    public $published;
    public $photo_name;
    
    private $inputFilter;
    private $path_to_save;
    

    public function exchangeArray(array $data)
    {
        $this->id     = !empty($data['id']) ? $data['id'] : null;
        $this->title = !empty($data['title']) ? $data['title'] : null;
        $this->content = !empty($data['content']) ? $data['content'] : null;
        $this->published = !empty($data['published']) ? $data['published'] : null;
        $this->photo_name = !empty($data['photo_name']) ? $data['photo_name'] : null;
    }
    
    public function getArrayCopy(){
        //$this->file = $file = file_put_contents($this->photo_name, file_get_contents($this->path_to_save.'/'.$this->photo_name));
        return [
            'id'     => $this->id,
            'title' => $this->title,
            'content'  => $this->content,
            'published'  => $this->published,
            'photo_name'  => $this->photo_name,
        ];
    }
    
     public function setInputFilter(InputFilterInterface $inputFilter){
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
        
        $inputFilter->add([
            'name' => 'content',
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
                        'max' => 300,
                    ],
                ],
            ],
        ]);
        
        // Add validation rules for the "file" field.	 
        $inputFilter->add(([
            'name'     => 'photo_name',
            'required' => false,
            'validators' => [
                ['name'    => 'FileUploadFile'],
                    [
                    'name'    => 'FileMimeType',                        
                    'options' => [                            
                            'mimeType'  => ['image/jpeg', 'image/png']
                             ]
                    ],
                    ['name'    => 'FileIsImage'],
                    [
                        'name'    => 'FileImageSize',
                            'options' => [
                                'minWidth'  => 128,
                                'minHeight' => 128,
                                'maxWidth'  => 4096,
                                'maxHeight' => 4096
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
             ])
        );

        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }
    
}

