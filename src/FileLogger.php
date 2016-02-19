<?php

namespace MacGhriogair\Logger;

class FileLogger extends Logger
{
    protected $logFile;
    public $level;

    public function __construct($level, $logFile)
    {
        $this->logFile = $logFile;
        $this->level = $level;
        $this->validateFile();
    }

    protected function handleRequest($message)
    {
        $h = fopen($this->logFile, "a");
        fputs($h, $message);
        fclose($h);
    }

    protected function resolveMessage($message, $levelName)
    {
        return sprintf(
            "%s [%s] %s\n",
            date("d.m.Y H:i:s"),
            $levelName,
            $message
        );
    }

    protected function validateFile()
    {
        $fp = null;
        if (!file_exists($this->logFile)) {
            exec("touch $this->logFile");
        }
        if (!is_writable($this->logFile)) {
            throw new Exception("Cannot not write file $this->logFile.", 1);
        }
        if (!$fp = fopen($this->logFile, 'a')) {
            throw new Exception("Could not open file $this->logFile.", 1);
        } else {
            fclose($fp);
        }
        return;
    }
}
