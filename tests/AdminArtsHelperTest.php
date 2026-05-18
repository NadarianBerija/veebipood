<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the adminArts helper methods.
 */
#[CoversClass(adminArts::class)]
final class AdminArtsHelperTest extends BaseTestCase
{
    /**
     * Test that sanitizePath method correctly removes invalid characters from a given string.
     */
    public function testSanitizePathRemovesInvalidCharacters(): void
    {
        $ref = new ReflectionClass(adminArts::class);
        $method = $ref->getMethod('sanitizePath');
        $method->setAccessible(true);

        $value = $method->invoke(null, 'Hello<> World!@#');

        $this->assertSame('HelloWorld', $value);
    }

    /**
     * Test that the clean method correctly trims whitespace from a given string.
     */
    public function testCleanTrimsWhitespace(): void
    {
        $ref = new ReflectionClass(adminArts::class);
        $method = $ref->getMethod('clean');
        $method->setAccessible(true);

        $value = $method->invoke(null, "  test value  ");

        $this->assertSame('test value', $value);
    }
}
