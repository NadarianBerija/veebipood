<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the main Controller class, covering basic page rendering and error handling.
 */
#[CoversClass(Controller::class)]
final class ControllerTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the database and session.
     */
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
    }

    /**
     * Test that the StartSite method returns HTML content containing the expected elements.
     */
    public function testStartSiteReturnsHtml(): void
    {
        $html = Controller::StartSite();

        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('Vihmart', $html);
    }

    /**
     * Test that the AboutUs method returns HTML content containing the expected elements.
     */
    public function testAboutUsReturnsHtmlContents(): void
    {
        $html = Controller::AboutUs();

        $this->assertStringContainsString('About', $html);
    }

    /**
     * Test that the Contact method returns HTML content containing the expected elements.
     */
    public function testContactReturnsHtmlContents(): void
    {
        $html = Controller::Contact();

        $this->assertStringContainsString('Contact', $html);
    }

    /**
     * Test that the error404 method sets the correct HTTP response code and returns HTML content containing the expected elements.
     */
    public function testError404SetsHttpResponseCode(): void
    {
        $html = Controller::error404();

        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('404', $html);
    }
}
