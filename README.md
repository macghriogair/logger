A PHP LogService that is PSR-3 compatible
=======================

Basic logging service that handles multiple logger implementations ( currently mail, stdOut, file).

Inspired by some blog posts on Chain of Responsibility pattern and logger examples there:
- https://alexanderjank.de/devblog/2015/02/logging-class-chain-of-responsibility
- http://dsheiko.com/subpage/chain-of-responsibility-pattern

Package under development.


## Usage example

    <?php

    use Macghriogair\Logger\LogService;
    use Macghriogair\Logger\ConsoleLogger;
    use Macghriogair\Logger\FileLogger;

    // 1. get singleton instance
    $service = LogService::getInstance();

    // 2. add as many loggers as needed
    $service->addLogger(new FileLogger(Logger::INFO, 'testlog.txt'));
    $service->addLogger(new ConsoleLogger(Logger::DEBUG));

    // 3. log something
    $service->log(Logger::WARN, 'Log entry'); // log w/ explicit level
    $service->debug('Debug log entry'); // shorthand
