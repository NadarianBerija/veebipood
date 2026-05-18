<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the AdminController, focusing on individual controller actions.
 */
#[CoversClass(controllerAdmin::class)]
final class AdminControllerTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = [];
        $_POST = [];
    }

    /**
     * Test that the formLoginSite method outputs the login form with a CSRF token.
     */
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
