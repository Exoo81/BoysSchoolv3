<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class AboutUsTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchOne(){
        
        $rowset = $this->tableGateway->select();
        $row = $rowset->current();
        
        return $row;
    }

    
    public function getAboutUs($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            //throw new RuntimeException(sprintf(
            //    'Could not find row with identifier %d',
           //     $id
           // ));
            return null;
        }

        return $row;
    }

    public function saveAboutUs(AboutUs $aboutUs)
    {
        $data = [
            'title' => $aboutUs->title,
            'content'  => $aboutUs->content,
            'principal_name'  => $aboutUs->principal_name,
        ];

        $id = (int) $aboutUs->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getAboutUs($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update About Us section with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

}

