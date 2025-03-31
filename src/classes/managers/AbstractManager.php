<?php

namespace App\managers;

use App\dal\mapper\AbstractMapper;

abstract class  AbstractManager
{
    public function getList(array $params = []): array
    {
        return $this->getMapper()->getList($params);
    }

    abstract public function getMapper(): AbstractMapper;
}






