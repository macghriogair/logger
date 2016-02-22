<?php

namespace Macghriogair\Logger\Tests;

use Macghriogair\Logger\FileLogger;
use Macghriogair\Logger\Logger;

class FileLoggerTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        exec('rm testlog.txt');
    }

    /** @test */
    public function it_has_a_loglevel()
    {
        $logger = new FileLogger(Logger::INFO, 'testlog.txt');

        $this->assertEquals(1, $logger->level);
    }

    /** @test */
    public function it_logs_to_a_file()
    {
        $logger = new FileLogger(Logger::INFO, 'testlog.txt');
        $logger->info('Hello.');

        $h = fopen('testlog.txt', 'r');
        $buffer = fgets($h, 100);
        $this->assertEquals('[INFO] Hello.', substr($buffer, 20, 13));
    }

    /** @test */
    public function it_not_logs_levels_lower_than_defined()
    {
        $logger = new FileLogger(Logger::WARN, 'testlog.txt');
        $logger->info('Hello.');

        $h = fopen('testlog.txt', 'r');
        $buffer = fgets($h, 100);
        $this->assertEquals('', $buffer);
    }

    /** @test */
    public function it_logs_levels_equal_as_defined()
    {
        $logger = new FileLogger(Logger::WARN, 'testlog.txt');
        $logger->warn('Hello.');

        $h = fopen('testlog.txt', 'r');
        $buffer = fgets($h, 100);
        $this->assertEquals('[WARN] Hello.', substr($buffer, 20, 13));
    }

    /** @test */
    public function it_logs_levels_higher_than_defined()
    {
        $logger = new FileLogger(Logger::WARN, 'testlog.txt');
        $logger->error('Hello.');

        $h = fopen('testlog.txt', 'r');
        $buffer = fgets($h, 100);
        $this->assertEquals('[ERROR] Hello.', substr($buffer, 20, 14));
    }

    /** @test */
    public function it_has_an_explicit_approach_with_same_result()
    {

        $logger = new FileLogger(Logger::WARN, 'testlog.txt');
        $logger->log('Hello.', Logger::ERROR);

        $h = fopen('testlog.txt', 'r');
        $buffer = fgets($h, 100);
        $this->assertEquals('[ERROR] Hello.', substr($buffer, 20, 14));
    }
}
