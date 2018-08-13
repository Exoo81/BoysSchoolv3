<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;

class NewsForm extends Form{
    
    
    public function __construct($name = null){
        
        // We will ignore the name provided to the constructor
        parent::__construct('news-form');
        
        // Set binary content encoding.
        $this->setAttribute('enctype', 'multipart/form-data');

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
                'label' => 'News content',
            ],
        ]);
        
         //for editNews->deleteImgFromNews
        //$this->add([
        //    'name' => 'url',
        ///    'type' => 'text',
            
        //]);
        
        //Add "file" field
        //$this->addFile();
        $this->add([
            'type'  => 'Zend\Form\Element\File',
            'name' => 'imgurl',
            'attributes' => [                
                'id' => 'imgurl',
                
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
    
    //add "File" field
    public function addFile(){
        //File input
        $file = new Element\File('imgurl');
        $file->setLabel('Upload image');
        $file->setAttribute('id', 'imgurl');
        
        $this->add($file);
    }
}

