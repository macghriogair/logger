<?php

namespace Macghriogair\Logger;

interface LoggerInterface
{
    public function log($message, $level = 0);

    public function debug($message);

    public function info($message);

    public function warn($message);

    public function error($message);
}
