<?php

namespace src\exceptions;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Throwable;

class CronJobException extends \Exception
{

    /**
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->logException($message);
    }

    /**
     * @param string $payload
     * @return void
     */
    private function logException(string $payload): void
    {
        $date = date('Y-m');
        $loggerPath = __DIR__."/../../resources/lc_exceptions/exceptions_{$date}.log";

        $logger = new Logger('channel-cron');
        $logger->pushHandler(new StreamHandler($loggerPath), Logger::DEBUG);
        $this->sendToTelegram($payload);

        $logger->error('LOG DATA: ' . $payload);
    }

    /**
     * @param string $message
     * @return void
     */
    private function sendToTelegram(string $message): void
    {
        //
    }
}