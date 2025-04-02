<?php

namespace App\managers;

use App\dal\mapper\AbstractMapper;

abstract class  AbstractManager
{
    public function getList(array $params = []): array
    {
        $orderBy = 'created_at DESC';
        return $this->getMapper()->getList($params, null, null, null, $orderBy);
    }

    abstract public function getMapper(): AbstractMapper;
}






