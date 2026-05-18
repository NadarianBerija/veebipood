<?php

use PHPUnit\Framework\TestCase;

/**
 * Base test case class for all tests, providing common setup and teardown functionality.
 * It also includes enhanced logging for test execution and failure details.
 */
abstract class BaseTestCase extends TestCase
{
    /**
     * Set up the test environment before each test, resetting the database and session.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $name = $this->name();
        $class = static::class;

        echo "[TEST START] {$class}::{$name}" . PHP_EOL;
    }

    /**
     * Tear down the test environment after each test, logging the end of the test.
     */
    protected function tearDown(): void
    {
        $name = $this->name();
        $class = static::class;

        echo "[TEST END] {$class}::{$name}" . PHP_EOL;

        parent::tearDown();
    }

    /**
     * Handle test failures, logging detailed information about the failure.
     */
    protected function onNotSuccessfulTest(\Throwable $t): never
    {
        $name = $this->name();
        $class = static::class;

        echo "[TEST FAILURE] {$class}::{$name}" . PHP_EOL;
        echo "Message: " . $t->getMessage() . PHP_EOL;
        echo "File: {$t->getFile()}:{$t->getLine()}" . PHP_EOL;

        $trace = $t->getTraceAsString();
        echo "Trace:" . PHP_EOL . $trace . PHP_EOL;

        parent::onNotSuccessfulTest($t);
    }
}
