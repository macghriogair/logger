<?php

namespace Macghriogair\Logger;

class ConsoleLogger extends AbstractLogger
{
    public $level;
    private $lineBreak;

    public function __construct($level)
    {
        $this->validateLevel($level);
        $this->level = $level;
        $this->lineBreak = ('cli' === php_sapi_name()) ? PHP_EOL : '<br />';
    }

    protected function process($message)
    {
        print($message);
    }

    protected function resolveMessage($message, $levelName)
    {
        return sprintf(
            "%s [%s] %s%s",
            date("d.m.Y H:i:s"),
            $levelName,
            $message,
            $this->lineBreak
        );
    }
}
