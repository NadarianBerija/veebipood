<?php

use PHPUnit\Framework\TestCase;

final class AdminControllerTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
        $_POST = [];
    }

    public function testFormLoginSiteOutputsLoginForm(): void
    {
        $_SESSION['csrf_token'] = 'token123';
        $currentDir = getcwd();
        chdir(__DIR__ . '/../admin');

        ob_start();
        controllerAdmin::formLoginSite();
        $output = ob_get_clean();

        chdir($currentDir);

        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('login', strtolower($output));
    }
}
