<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;

class NewsletterTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        $select = new Select($this->tableGateway->getTable());
        $select->order('published DESC');
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        //$resultSet->next();
        return $resultSet;
    }

    public function getNewsletter($id)
    {
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

    public function saveNewsletter(Newsletter $newsletter){
        $date = date('Y-m-d H:i:s');
        $newsletter->published = $date;
        
        $data = [
            'title'  => $newsletter->title,
            'published'  => $newsletter->published,
            'file_name'  => $newsletter->file_name,
        ];

        $id = (int) $newsletter->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getNewsletter($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteNewsletter($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

