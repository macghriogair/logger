<?php

namespace Macghriogair\Logger;

abstract class Logger implements LoggerInterface
{
    const DEBUG = 0;
    const INFO = 1;
    const WARN = 2;
    const ERROR = 3;

    public $level = -1;

    private $constantNames = null;

    public function log($message, $level = 0)
    {
        if ($this->shouldLog($level)) {
            $levelName = $this->getConstant($level);
            $message = $this->resolveMessage($message, $levelName);
            $this->handleRequest($message);
        }
    }

    public function debug($message)
    {
        $this->log($message, self::DEBUG);
    }

    public function info($message)
    {
        $this->log($message, self::INFO);
    }

    public function warn($message)
    {
        $this->log($message, self::WARN);
    }

    public function error($message)
    {
        $this->log($message, self::ERROR);
    }

    public function shouldLog($level)
    {
        return $level >= $this->level;
    }

    protected function getConstant($level)
    {
        if (null === $this->constantNames) {
            $r = new \ReflectionClass(__CLASS__);
            $this->constantNames = array_flip($r->getConstants());
        }
        return $this->constantNames[$level];
    }

    abstract protected function resolveMessage($message, $levelName);
    abstract protected function handleRequest($message);
}
