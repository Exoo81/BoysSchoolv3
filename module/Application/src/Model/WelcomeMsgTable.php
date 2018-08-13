<?php

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;

class WelcomeMsgTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchOne()
    {
        $rowset = $this->tableGateway->select();
        $row = $rowset->current();
        
        return $row;
    }

    public function getWelcomeMsg($id)
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

    public function saveWelcomeMsg(WelcomeMsg $welcomeMsg)
    {
        $data = [
            'content' => $welcomeMsg->content,
        ];

        $id = (int) $welcomeMsg->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        if (! $this->getWelcomeMsg($id)) {
            throw new RuntimeException(sprintf(
                'Cannot update welcome message with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteWelcomeMsg($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

