<?php

namespace MacGhriogair\Logger;

class Service implements LoggerInterface
{
    protected static $instance = null;

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

    public function log($message, $logLevel = 0)
    {
        array_walk(
            $this->loggers,
            function ($l) use ($message, $logLevel) {
                $l->log($message, $logLevel);
            }
        );
        return $this;
    }

    public function debug($message)
    {
        return $this->log($message, Logger::DEBUG);
    }

    public function info($message)
    {
        return $this->log($message, Logger::INFO);
    }

    public function warn($message)
    {
        return $this->log($message, Logger::WARN);
    }

    public function error($message)
    {
        return $this->log($message, Logger::ERROR);
    }
}
