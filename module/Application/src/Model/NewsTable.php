<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;


class NewsTable{
    
    
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated=false){
        if ($paginated) {
            return $this->fetchPaginatedResults();
        }
        
        return $this->tableGateway->select();
    }
    
    private function fetchPaginatedResults(){
        // Create a new Select object for the table:
        $select = new Select($this->tableGateway->getTable());

        // Create a new result set based on the News entity:
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new News());

        // Create a new pagination adapter object:
        $paginatorAdapter = new DbSelect(
            // our configured select object:
            $select->order('published DESC'),
            // the adapter to run it against:
            $this->tableGateway->getAdapter(),
            // the result set to hydrate:
            $resultSetPrototype
        );

        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }

    public function getNews($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            //throw new RuntimeException(sprintf(
            //    'Could not find row with identifier %d',
            //    $id
            //));
            return null;
        }

        return $row;
    }

    public function saveNews(News $news){
        $date = date('Y-m-d H:i:s');
        $news->published = $date;
        
        $data = [
            'title' => $news->title,
            'content' => $news->content,
            'published' => $news->published,
            //'photo_name' => $news->photo_name['name'],
            'photo_name' => $news->photo_name,
        ];

        $id = (int) $news->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getNews($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update news with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }
    
    public function saveNewsNoImg(News $news){
        $date = date('Y-m-d');
        $news->published = $date;
        
        $data = [
            'title' => $news->title,
            'content' => $news->content,
            'published' => $news->published,
        ];

        $id = (int) $news->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getNews($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update news with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteNews($id){
        $this->tableGateway->delete(['id' => (int) $id]);
    }
    
    public function deleteImgFromNews($id, $delLocs){
        $data = [
            'photo_name' => '',
        ];
        
        
        // if file exist 
       // if($delLocs !=null){
            // update table
            $this->tableGateway->update($data, ['id' => $id]);
            // remove file
            //unlink($delLocs) or die("Couldn't delete file");
       // }else{
            //$this->tableGateway->update($data, ['id' => $id]);
        //}
        

    }
}
