<?php

namespace Macghriogair\Logger\Tests;

use Macghriogair\Logger\Logger;
use Macghriogair\Logger\LogService;

class LogServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    public function setUp()
    {
        $this->service = LogService::getInstance();
    }

    /** @test */
    public function it_accepts_a_logger_interface()
    {
        $mailLoggerStub = $this->getLoggerStub();

        $this->service->addLogger($mailLoggerStub);
        $this->service->log('Hello World', Logger::ERROR);
    }

    /** @test */
    public function it_passes_through_a_log_request()
    {
        $loggerMock = $this->getLoggerStub();

        $loggerMock->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', Logger::ERROR));

        $this->service->addLogger($loggerMock);
        $this->service->log('Hello World', Logger::ERROR);
    }

    /** @test */
    public function it_passes_through_to_every_logger()
    {
        $loggerMock = $this->getLoggerStub();
        $loggerMock2 = $this->getLoggerStub();

        $loggerMock->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', Logger::INFO));
        $loggerMock2->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', Logger::INFO));

        $this->service->addLogger($loggerMock)->addLogger($loggerMock2);
        $this->service->log('Hello World', Logger::INFO);
    }

    protected function getLoggerStub()
    {
        return $this->getMock('Macghriogair\Logger\LoggerInterface');
    }
}
