<?php

namespace Macghriogair\Logger;

use Macghriogair\Logger\Exception\FileLoggerException;

class FileLogger extends AbstractLogger
{
    public $level;
    protected $logFile;

    public function __construct($level, $logFile)
    {
        $this->validateLevel($level);
        $this->level = $level;
        $this->logFile = $logFile;
        $this->validateFile();
    }

    protected function process($message)
    {
        $h = fopen($this->logFile, "a");
        fputs($h, $message);
        fclose($h);
    }

    protected function resolveMessage($message, $levelName)
    {
        return sprintf(
            "%s [%s] %s.\n",
            date("d.m.Y H:i:s"),
            $levelName,
            $message
        );
    }

    /**
     * @throws FileLoggerException
     */
    protected function validateFile()
    {
        $fp = null;
        if (! file_exists($this->logFile)) {
            exec("touch $this->logFile");
        }
        if (! is_writable($this->logFile)) {
            throw new FileLoggerException("Cannot not write file $this->logFile.", 1);
        }
        if (! $fp = fopen($this->logFile, 'a')) {
            throw new FileLoggerException("Could not open file $this->logFile.", 1);
        } else {
            fclose($fp);
        }
        return;
    }
}
