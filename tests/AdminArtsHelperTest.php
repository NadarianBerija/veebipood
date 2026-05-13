<?php

use PHPUnit\Framework\TestCase;

final class AdminArtsHelperTest extends BaseTestCase
{
    public function testSanitizePathRemovesInvalidCharacters(): void
    {
        $ref = new ReflectionClass(adminArts::class);
        $method = $ref->getMethod('sanitizePath');
        $method->setAccessible(true);

        $value = $method->invoke(null, 'Hello<> World!@#');

        $this->assertSame('HelloWorld', $value);
    }

    public function testCleanTrimsWhitespace(): void
    {
        $ref = new ReflectionClass(adminArts::class);
        $method = $ref->getMethod('clean');
        $method->setAccessible(true);

        $value = $method->invoke(null, "  test value  ");

        $this->assertSame('test value', $value);
    }
}
