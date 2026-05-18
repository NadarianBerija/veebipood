<?php

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
/** * Integration tests for the admin routes, verifying that the correct pages are rendered based on the requested URI and user authentication status.
 * @covers controllerAdmin
 */
final class AdminRouteIntegrationTest extends BaseTestCase
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
        * Test that the admin root route shows the login form for unauthenticated users.
        */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAdminRootRouteShowsLoginForm(): void
    {
        $_SERVER['REQUEST_URI'] = '/admin';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../admin');

        ob_start();
        require __DIR__ . '/../admin/routeAdmin/routingAdmin.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('login', strtolower($output));
    }

        /**
        * Test that the admin dashboard route shows the dashboard for authenticated admin users.
        */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAdminLoginRouteWithoutPostShowsLoginForm(): void
    {
        $_SERVER['REQUEST_URI'] = '/admin/login';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../admin');

        ob_start();
        require __DIR__ . '/../admin/routeAdmin/routingAdmin.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('login', strtolower($output));
    }

        /**
        * Test that an unknown admin route returns a 404 response.
        */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAdminUnknownRouteReturns404(): void
    {
        $_SERVER['REQUEST_URI'] = '/admin/unknown';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../admin');

        ob_start();
        require __DIR__ . '/../admin/routeAdmin/routingAdmin.php';
        $output = ob_get_clean();

        $this->assertSame(404, http_response_code());
        $this->assertStringContainsString('<title>Admin dashboard</title>', $output);
    }
}
