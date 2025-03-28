<?php

namespace App\managers;

use App\dal\mapper\AbstractMapper;

abstract class  AbstractManager
{
    public function getList()
    {
        return $this->getMapper()->getList();
    }

    abstract public function getMapper():AbstractMapper;
}






