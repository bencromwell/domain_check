<?php

namespace DomainCheck;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;

class Log
{
    const NS = 'domain_check';

    public function logger(int $level = Logger::NOTICE): LoggerInterface
    {
        $path = __DIR__ . '/../log/domain_check.log';

        $logger = new Logger(self::NS);

        $handler = new StreamHandler($path, $level);
        $handler->pushProcessor(new UidProcessor());

        $logger->pushHandler($handler);

        return $logger;
    }
}
