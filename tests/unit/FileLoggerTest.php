<?php

namespace Macghriogair\Logger\Tests;

use Macghriogair\Logger\FileLogger;
use Psr\Log\LogLevel;

class FileLoggerTest extends \PHPUnit_Framework_TestCase
{
    const LOG_FILE = 'testlog.txt';

    public function tearDown()
    {
        @unlink(self::LOG_FILE);
    }

    /** @test */
    public function it_has_a_loglevel()
    {
        $logger = new FileLogger(LogLevel::INFO, self::LOG_FILE);

        $this->assertEquals(LogLevel::INFO, $logger->level);
    }

    /** @test */
    public function it_logs_to_a_file()
    {
        $logger = new FileLogger(LogLevel::INFO, self::LOG_FILE);
        $logger->info('Hello');

        $this->assertLogHas('[INFO] Hello.');
    }

    /** @test */
    public function it_not_logs_levels_lower_than_defined()
    {
        $logger = new FileLogger(LogLevel::WARNING, self::LOG_FILE);
        $logger->info('Hello');

        $this->assertLogIsEmpty();
    }

    /** @test */
    public function it_logs_levels_equal_as_defined()
    {
        $logger = new FileLogger(LogLevel::WARNING, self::LOG_FILE);
        $logger->warning('Hello');

        $this->assertLogHas('[WARNING] Hello.');
    }

    /** @test */
    public function it_logs_levels_higher_than_defined()
    {
        $logger = new FileLogger(LogLevel::WARNING, self::LOG_FILE);
        $logger->error('Hello');

        $this->assertLogHas('[ERROR] Hello.');
    }

    /** @test */
    public function it_has_an_explicit_approach_with_same_result()
    {
        $logger = new FileLogger(LogLevel::WARNING, self::LOG_FILE);
        $logger->log(LogLevel::ERROR, 'Hello');

        $this->assertLogHas('[ERROR] Hello.');
    }

    /** @test */
    public function it_interpolates_message_with_context()
    {
        $logger = new FileLogger(LogLevel::DEBUG, self::LOG_FILE);
        $logger->log(LogLevel::DEBUG, 'Login attempt by {username}', ['username' => 'Bart Simpson']);

        $this->assertLogHas('[DEBUG] Login attempt by Bart Simpson.');
    }


    protected function assertLogHas($expected)
    {
        $withoutTimestamp = substr($this->readLine(), 20, 100) ?: '';
        $this->assertStringStartsWith($expected, $withoutTimestamp);

    }

    protected function assertLogIsEmpty()
    {
        $withoutTimestamp = substr($this->readLine(), 20, 100) ?: '';
        $this->assertEquals('', $withoutTimestamp);
    }

    protected function readLine()
    {
        $h = fopen(self::LOG_FILE, 'r');
        $buffer = fgets($h, 100);
        fclose($h);
        return $buffer;
    }
}
