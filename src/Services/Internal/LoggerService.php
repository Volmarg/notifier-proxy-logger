<?php

namespace App\Services\Internal;

use Psr\Log\LoggerInterface;
use Throwable;

class LoggerService
{
    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Will create critical log entry for Thrown Exception
     *
     * @param Throwable $e
     * @param array $context
     */
    public function logThrowable(Throwable $e, array $context = []): void
    {
        $this->logger->critical("Exception was thrown", [
            "message" => $e->getMessage(),
            "trace"   => $e->getTrace(),
            "context" => $context,
        ]);
    }


}