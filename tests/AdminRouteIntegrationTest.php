<?php

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

final class AdminRouteIntegrationTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
        $_SERVER = [];
    }

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
