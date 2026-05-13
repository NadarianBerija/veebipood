<?php

use PHPUnit\Framework\TestCase;

final class ControllerTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
    }

    public function testStartSiteReturnsHtml(): void
    {
        $html = Controller::StartSite();

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('Vihmart', $html);
    }

    public function testAboutUsReturnsHtmlContents(): void
    {
        $html = Controller::AboutUs();

        $this->assertStringContainsString('About', $html);
    }

    public function testContactReturnsHtmlContents(): void
    {
        $html = Controller::Contact();

        $this->assertStringContainsString('Contact', $html);
    }

    public function testError404SetsHttpResponseCode(): void
    {
        $html = Controller::error404();

        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('404', $html);
    }
}
