<?php

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $name = $this->name();
        $class = static::class;

        echo "[TEST START] {$class}::{$name}" . PHP_EOL;
    }

    protected function tearDown(): void
    {
        $name = $this->name();
        $class = static::class;

        echo "[TEST END] {$class}::{$name}" . PHP_EOL;

        parent::tearDown();
    }

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
