<?php
/**
 * @date    2016-11-15
 * @file    MailLoggerTest.php
 * @author  Patrick Mac Gregor <pmacgregor@3pc.de>
 */

namespace Tests;

use Macghriogair\Logger\MailLogger;
use Psr\Log\LogLevel;

class MailLoggerTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function it_has_an_option_to_release_messages_only_when_requested()
    {
        $logger = new MailLogger(LogLevel::DEBUG, [], false);
        $logger->debug('My queued message.');

        $this->assertCount(1, $logger->getMessages());
        $logger->release();
        $this->assertCount(0, $logger->getMessages());
    }
}
