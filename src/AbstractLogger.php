<?php

namespace Macghriogair\Logger;

use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Psr\Log\LogLevel;

abstract class AbstractLogger implements LoggerInterface
{
    use LoggerTrait;

    public $level = null;

    protected $levelNames = null;

    protected $immediateRelease = true;
    protected $messages = array();

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = array())
    {
        $this->validateLevel($level);
        if (! $this->shouldLog($level)) {
            return;
        }

        $message = $this->resolveMessage(
            $this->interpolate($message, $context),
            strtoupper($level)
        );

        if ($this->immediateRelease) {
            $this->process($message);
        } else {
            $this->messages[] = $message;
        }
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return mixed
     */
    public function release()
    {
        $combinedMessages = implode('', $this->getMessages());
        $this->messages = [];
        return $this->process($combinedMessages);
    }

    /**
     * @param $level
     * @return bool
     */
    protected function shouldLog($level)
    {
        return $this->toInt($this->level) <= $this->toInt($level);
    }

    /**
     * @param $levelName
     * @return int
     */
    protected function toInt($levelName)
    {
        $levelAsInt = array(
            LogLevel::EMERGENCY => 7,
            LogLevel::ALERT     => 6,
            LogLevel::CRITICAL  => 5,
            LogLevel::ERROR     => 4,
            LogLevel::WARNING   => 3,
            LogLevel::NOTICE    => 2,
            LogLevel::INFO      => 1,
            LogLevel::DEBUG     => 0
        );
        return isset($levelAsInt[$levelName]) ? $levelAsInt[$levelName] : -1;
    }

    /**
     * @param $level
     * @throws InvalidArgumentException
     */
    protected function validateLevel($level)
    {
        if (! $this->isKnownLevel($level)) {
            throw new InvalidArgumentException("Invalid LogLevel: {$level}");
        }
    }

    /**
     * @param $level
     * @return bool
     */
    protected function isKnownLevel($level)
    {
        return in_array($level, $this->getLevelNames());
    }

    /**
     * @return array
     */
    protected function getLevelNames()
    {
        if (null === $this->levelNames) {
            $r = new \ReflectionClass(LogLevel::class);
            $this->levelNames = $r->getConstants();
        }
        return $this->levelNames;
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param $message
     * @param array $context
     * @return string
     */
    protected function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * @param $message
     * @param $levelName
     * @return string
     */
    abstract protected function resolveMessage($message, $levelName);

    /**
     * @param $message
     */
    abstract protected function process($message);
}
