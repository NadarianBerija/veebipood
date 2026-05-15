<?php

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

final class RouteIntegrationTest extends BaseTestCase
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
    public function testHomeRouteReturnsStartSiteHtml(): void
    {
        $_SERVER['REQUEST_URI'] = '/vihmart/';
        $_SERVER['QUERY_STRING'] = '';

        chdir(__DIR__ . '/../');
        require __DIR__ . '/../route/routing.php';

        $this->assertStringContainsString('<!DOCTYPE html>', $response);
        $this->assertStringContainsString('Vihmart', $response);
    }

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
