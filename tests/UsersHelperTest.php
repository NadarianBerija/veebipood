<?php

use PHPUnit\Framework\TestCase;

final class UsersHelperTest extends BaseTestCase
{
    public function testCleanTrimsWhitespace(): void
    {
        $ref = new ReflectionClass(Users::class);
        $method = $ref->getMethod('clean');
        $method->setAccessible(true);

        $value = $method->invoke(null, "  example  ");

        $this->assertSame('example', $value);
    }
}
