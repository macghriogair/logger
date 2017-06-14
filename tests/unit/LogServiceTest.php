<?php

namespace Macghriogair\Logger\Tests;

use Macghriogair\Logger\LogService;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class LogServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Macghriogair\Logger\LogService */
    protected $service;

    public function setUp()
    {
        $this->service = LogService::getInstance();
    }

    /** @test */
    public function it_accepts_a_logger_interface()
    {
        $loggerStub = $this->getLoggerStub();

        $this->service->addLogger($loggerStub);
        $this->service->log('Hello World', LogLevel::ERROR);
    }

    /** @test */
    public function it_passes_through_a_log_request()
    {
        $loggerMock = $this->getLoggerStub();

        $loggerMock->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', LogLevel::ERROR));

        $this->service->addLogger($loggerMock);
        $this->service->log(LogLevel::ERROR, 'Hello World');
    }

    /** @test */
    public function it_passes_through_to_every_logger()
    {
        $loggerMock = $this->getLoggerStub();
        $loggerMock2 = $this->getLoggerStub();

        $loggerMock->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', LogLevel::INFO));
        $loggerMock2->expects($this->once())
                     ->method('log')
                     ->with($this->identicalTo('Hello World', LogLevel::INFO));

        $this->service->addLogger($loggerMock)->addLogger($loggerMock2);
        $this->service->log(LogLevel::INFO, 'Hello World');
    }

    protected function getLoggerStub()
    {
        return $this->createMock(LoggerInterface::class);
    }
}
