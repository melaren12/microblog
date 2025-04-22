<?php

namespace App\dal\mapper;

use App\util\LogHelper;
use Exception;
use PDO;

abstract class  AbstractMapper extends AbstractPDOConnector
{
    protected string $tableName;

    protected PDO $pdo;

    /**
     * @throws Exception
     */
    public function insertList(array $params = []): int
    {
        if (empty($params)) {
            return -1;
        }
        foreach ($params as $record) {
            if (!is_array($record)) {
                LogHelper::getInstance()->createErrorLog('insertList error: ' .
                    'Each element in \$params must be an array in the format [column => value].');
                throw new Exception("Format Error");
            }
        }

        $tableName = $this->getTableName();

        $firstRecord = reset($params);
        $fieldNames = array_keys($firstRecord);
        if (empty($fieldNames)) {
            LogHelper::getInstance()->createInfoLog('insertList error: ' . 'No fields to insert.');
            throw new Exception("Error");
        }

        foreach ($params as $record) {
            if (array_keys($record) !== $fieldNames) {
                LogHelper::getInstance()->createInfoLog('insertList error: ' . 'All records must have the same set of fields.');
                throw new Exception("Error");
            }
        }

        $placeholders = array_fill(0, count($fieldNames), '?');
        $singleValuePlaceholder = '(' . implode(', ', $placeholders) . ')';

        $valuePlaceholders = array_fill(0, count($params), $singleValuePlaceholder);
        $sql = "INSERT INTO $tableName (" . implode(', ', $fieldNames) . ") VALUES " . implode(', ', $valuePlaceholders);

        $values = [];
        foreach ($params as $record) {
            $values = array_merge($values, array_values($record));
        }

        $stmt = $this->PDO->prepare($sql);
        $stmt->execute($values);

        return (int)$this->PDO->lastInsertId();
    }

    public function getList(
        array   $params = [],
        ?string $selectFields = null,
        ?int    $limit = null,
        ?string $join = null,
        ?string $orderBy = null,
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