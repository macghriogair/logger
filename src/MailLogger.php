<?php

namespace MacGhriogair\Logger;

class MailLogger extends Logger
{
    public $level;

    private $subscribers = array();

    public function __construct($level, $subscribers)
    {
        $this->level = $level;
        $this->subscribers = $this->validateSubscribers($subscribers);
    }

    protected function handleRequest($message)
    {
        array_walk(
            $this->subscribers,
            function ($recipient) use ($message) {
                mail($recipient, 'Message from: ' . __CLASS__, $message);
            }
        );
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
}
