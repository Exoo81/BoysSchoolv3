<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class SubscribeTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway){
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll(){
        return $this->tableGateway->select();
    }

    public function getSubscribe($id){
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }
    
    // for check email exist
    public function getSubscribeByEmail($email){
        $email = (string) $email;
        $rowset = $this->tableGateway->select(['email' => $email]);
        $row = $rowset->current();
        if (! $row) {
            //throw new RuntimeException(sprintf(
            //    'Could not find row with identifier %d',
            //    $email
            //));
            return null;
        }

        return $row;
    }

    public function saveSubscribe(Subscribe $subscribe)
    {
        $data = [
            'email' => $subscribe->email,
        ];

        $id = (int) $subscribe->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getSubscribe($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update subscriptions with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteSubscribe($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

