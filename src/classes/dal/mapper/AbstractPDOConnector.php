<?php

namespace App\dal\mapper;
 use App\util\LogHelper;
 use PDO;
 use PDOException;
 abstract class  AbstractPDOConnector
{
    protected PDO $PDO;
    public function __construct()
    {
        $host = 'localhost';
        $db = 'microblog';
        $user = 'root';
        $pass = 'Ar0126#00';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->PDO = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            LogHelper::getInstance()->createErrorLog('Database connection failed: ' . $e->getMessage());
            echo "Database connection failed.";
            exit;
        }
    }
    abstract function getTableName();
}






