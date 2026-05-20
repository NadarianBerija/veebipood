<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Integration tests for the main routing of the application, verifying that the correct pages are rendered based on the requested URI.
 */
#[CoversClass('routing')]
final class RouteIntegrationTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the database and session.
     */
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
    }

    /**
     * Test that the home route returns the StartSite HTML content.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testHomeRouteReturnsStartSiteHtml(): void
    {
        $_SERVER['REQUEST_URI'] = '/vihmart/';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../');
        require __DIR__ . '/../route/routing.php';

        $this->assertStringContainsString('<!DOCTYPE html>', $response);
        $this->assertStringContainsString('Vihmart', $response);
    }

    /**
     * Test that the about us route returns the AboutUs HTML content.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAboutUsRouteReturnsAboutUsPage(): void
    {
        $_SERVER['REQUEST_URI'] = '/vihmart/aboutUs';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../');
        require __DIR__ . '/../route/routing.php';

        $this->assertStringContainsString('About', $response);
    }

    /**
     * Test that the contact route returns the Contact HTML content.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testShopRouteReturnsShopPage(): void
    {
        $_SERVER['REQUEST_URI'] = '/vihmart/shop';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../');
        require __DIR__ . '/../route/routing.php';

        $this->assertStringContainsString('<!DOCTYPE html>', $response);
        $this->assertStringContainsString('Shop', $response);
    }

    /**
     * Test that an unknown route returns a 404 response with the expected content.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testUnknownRouteReturns404(): void
    {
        $_SERVER['REQUEST_URI'] = '/vihmart/unknown-path';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../');
        require __DIR__ . '/../route/routing.php';

        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('404', $response);
    }
}
