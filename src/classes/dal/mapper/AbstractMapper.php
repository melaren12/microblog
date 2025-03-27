<?php

namespace App\dal\mapper;

abstract class  AbstractMapper extends AbstractPDOConnector
{
    protected string $tableName;

    public function getList()
    {
        $stmt = $this->PDO->prepare("SELECT * FROM " . $this->getTableName());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getItemById(int $id)
    {
        $stmt = $this->PDO->prepare("SELECT * FROM " . $this->getTableName() . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }
}






