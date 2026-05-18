<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Users helper class, focusing on the cleaning of input data.
 */
#[CoversClass(Users::class)]
final class UsersHelperTest extends BaseTestCase
{
    /**
     * Test that the clean method correctly trims whitespace from a given string.
     */
    public function testCleanTrimsWhitespace(): void
    {
        $ref = new ReflectionClass(Users::class);
        $method = $ref->getMethod('clean');
        $method->setAccessible(true);

        $value = $method->invoke(null, "  example  ");

        $this->assertSame('example', $value);
    }
}
