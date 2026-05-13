<?php

use PHPUnit\Framework\TestCase;

final class LangTest extends BaseTestCase
{
    protected function setUp(): void
    {
        $ref = new ReflectionClass(Lang::class);
        $prop = $ref->getProperty('data');
        $prop->setAccessible(true);
        $prop->setValue([]);
    }

    public function testLoadReturnsExistingLanguageValue(): void
    {
        Lang::load('lang');

        $this->assertSame('Home', Lang::get('home'));
    }

    public function testGetReturnsKeyWhenMissing(): void
    {
        Lang::load('lang');

        $this->assertSame('missing_key', Lang::get('missing_key'));
    }
}
