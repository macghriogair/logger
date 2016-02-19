<?php

namespace MacGhriogair\Logger;

class ConsoleLogger extends Logger
{
    public $level;
    private $lineBreak;

    public function __construct($level)
    {
        $this->level = $level;
        $this->lineBreak = ('cli' === php_sapi_name()) ? PHP_EOL : '<br />';
    }

    protected function handleRequest($message)
    {
        print($message);
    }

    protected function resolveMessage($message, $levelName)
    {
        return sprintf(
            "%s [%s] %s".$this->lineBreak,
            date("d.m.Y H:i:s"),
            $levelName,
            $message
        );
    }
}
