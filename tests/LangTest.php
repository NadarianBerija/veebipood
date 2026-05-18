<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Lang class, focusing on the loading and retrieval of language data.
 * @covers Lang
 */
final class LangTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the language data.
     */
    protected function setUp(): void
    {
        $ref = new ReflectionClass(Lang::class);
        $prop = $ref->getProperty('data');
        $prop->setAccessible(true);
        $prop->setValue([]);
    }

    /**
     * Test that the load method correctly loads language data from a file and that the get method retrieves existing values.
     */
    public function testLoadReturnsExistingLanguageValue(): void
    {
        Lang::load('lang');

        $this->assertSame('Home', Lang::get('home'));
    }

        /**
        * Test that the get method returns the key itself when the requested language key is missing.
        */
    public function testGetReturnsKeyWhenMissing(): void
    {
        Lang::load('lang');

        $this->assertSame('missing_key', Lang::get('missing_key'));
    }
}
