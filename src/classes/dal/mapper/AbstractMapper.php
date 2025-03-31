<?php

namespace App\dal\mapper;

use PDO;

abstract class  AbstractMapper extends AbstractPDOConnector
{
    protected string $tableName;

    protected PDO $pdo;


    public function getList(array $params = []): array
    {
        $sql = "SELECT * FROM " . $this->getTableName();
        $whereConditions = [];
        $whereValues = [];

        foreach ($params as $key => $value) {
            $whereConditions[] = "$key = ?";
            $whereValues[] = $value;
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }

        $stmt = $this->PDO->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->createDto()::class);
        $stmt->execute($whereValues);

        return $stmt->fetchAll();
    }

    public function getItemById(int $id)
    {
        $stmt = $this->PDO->prepare("SELECT * FROM " . $this->getTableName() . " WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->createDto()::class);
        return $stmt->fetch();
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    abstract function createDto();
}






