<?php

namespace Macghriogair\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class LogService implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var \Macghriogair\Logger\LogService
     */
    protected static $instance = null;

    /**
     * @var array
     */
    protected $loggers = array();

    /**
    * Prevent direct object creation
    */
    private function __construct()
    {
    }

    /**
    * Prevent object cloning
    */
    private function __clone()
    {
    }

    /**
     * @return \Macghriogair\Logger\LogService
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function addLogger(LoggerInterface $logger)
    {
        $this->loggers[] = $logger;
        return $this;
    }

    public function log($level, $message, array $context = array())
    {
        array_walk(
            $this->loggers,
            function (LoggerInterface $l) use ($message, $level) {
                $l->log($message, $level);
            }
        );
        return $this;
    }
}
