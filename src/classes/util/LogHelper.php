<?php

namespace App\util;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class  LogHelper
{
    private static ?self $instance = null;
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('app');
    }
    public static function getInstance(): LogHelper
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function createErrorLog(string $message): void
    {
        $this->logger->pushHandler(new StreamHandler(dirname(__DIR__, 3) . '/data/log/error_' . date('Y-m-d') . '.log', Logger::ERROR));

        $this->logger->error($message);
    }

    public function createInfoLog(string $message): void
    {
        $this->logger->pushHandler(new StreamHandler(dirname(__DIR__, 3) . '/data/log/info_' . date('Y-m-d') . '.log', Logger::INFO));

        $this->logger->info($message);
    }
}