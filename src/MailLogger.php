<?php

namespace Macghriogair\Logger;

class MailLogger extends AbstractLogger
{
    public $level;

    protected $immediateRelease;
    protected $subscribers = array();

    public function __construct($level, $subscribers, $immediateRelease = true)
    {
        $this->validateLevel($level);
        $this->level = $level;
        $subscribers = $this->normalizeArray($subscribers);
        $this->subscribers = $this->validateSubscribers($subscribers);

        $this->immediateRelease = $immediateRelease;
    }

    protected function process($message)
    {
        array_walk(
            $this->subscribers,
            function ($recipient) use ($message) {
                mail($recipient, 'Message from: ' . __CLASS__, $message);
            }
        );
        return $this;
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

    protected function validateSubscribers($subscribers)
    {
        return array_filter($subscribers, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });
    }

    protected function normalizeArray($input)
    {
        return is_array($input) ? $input : array($input);
    }
}
