<?php

namespace App\dal\mapper;

use PDO;

abstract class  AbstractMapper extends AbstractPDOConnector
{
    protected string $tableName;

    protected PDO $pdo;

    public function getList(
        array $params = [],
        ?string $selectFields = null,
        ?int $limit = null,
        ?string $join = null,
        ?string $orderBy = null
    ): array
    {
        $fields = $selectFields ?: '*';
        $sql = "SELECT $fields FROM " . $this->getTableName();

        if ($join) {
            $sql .= " " . $join;
        }

        $whereConditions = [];
        $whereValues = [];

        $fetchMode = $params['fetchMode'] ?? PDO::FETCH_CLASS;
        $fetchModeArg = null;

        if ($fetchMode === PDO::FETCH_CLASS) {
            $fetchModeArg = $this->createDto()::class;
        }

        unset($params['fetchMode']);

        foreach ($params as $key => $value) {
            $whereConditions[] = "$key = ?";
            $whereValues[] = $value;
        }

        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }

        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . $limit;
        }

        $stmt = $this->PDO->prepare($sql);

        if ($fetchMode === PDO::FETCH_CLASS) {
            $stmt->setFetchMode($fetchMode, $fetchModeArg);
        } else {
            $stmt->setFetchMode($fetchMode);
        }

        $stmt->execute($whereValues);

        return $stmt->fetchAll();
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    abstract function createDto();
}






