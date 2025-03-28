<?php

namespace App\dal\mapper;

use PDO;

abstract class  AbstractMapper extends AbstractPDOConnector
{
    protected string $tableName;

    protected PDO $pdo;


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






