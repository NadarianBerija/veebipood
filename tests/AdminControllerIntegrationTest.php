<?php

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Integration tests for the AdminController, focusing on page rendering and access control within the admin panel.
 * @covers controllerAdmin
 */
final class AdminControllerIntegrationTest extends BaseTestCase
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
        $_FILES = [];
        $_SERVER = [];
    }

    /**
     * Helper method to simulate an admin user login.
     */
    private function loginAsAdmin(): void
    {
        $_SESSION['sessionId'] = 'session123';
        $_SESSION['userId'] = 1;
        $_SESSION['status'] = 'admin';
        $_SESSION['name'] = 'Admin User';
        $_SESSION['csrf_token'] = 'csrf123';
    }

        /**
     * Test that the hero slides management page renders correctly for an admin user.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testHeroSlidesPageRendersForAdmin(): void
    {
        $this->loginAsAdmin();

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM hero_slides hs'),
            [
                ['slide_id' => 1, 'slide_img' => 'images/slide1.jpg']
            ]
        );

        $currentDir = getcwd();
        chdir(__DIR__ . '/../admin');

        ob_start();
        controllerAdmin::HeroSlides();
        $output = ob_get_clean();

        chdir($currentDir);

        $this->assertStringContainsString('Slaidid', $output);
        $this->assertStringContainsString('images/slide1.jpg', $output);
    }

        /**
     * Test that the user management page renders correctly for an admin user.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testUsersPageRendersForAdmin(): void
    {
        $this->loginAsAdmin();

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM users u'),
            [
                ['user_id' => 1, 'user_name' => 'Test User', 'picture' => null, 'user_status' => 'moderaator', 'is_deleted' => 0]
            ]
        );

        $currentDir = getcwd();
        chdir(__DIR__ . '/../admin');

        ob_start();
        controllerAdmin::Users();
        $output = ob_get_clean();

        chdir($currentDir);

        $this->assertStringContainsString('Kasutajad', $output);
        $this->assertStringContainsString('Test User', $output);
    }

        /**
     * Test that the add user form renders correctly for an admin user.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAddUserFormRendersForAdmin(): void
    {
        $this->loginAsAdmin();

        $currentDir = getcwd();
        chdir(__DIR__ . '/../admin');

        ob_start();
        controllerAdmin::AddUserForm();
        $output = ob_get_clean();

        chdir($currentDir);

        $this->assertStringContainsString('<form', $output);
        $this->assertStringContainsString('name="login"', $output);
        $this->assertStringContainsString('name="password"', $output);
    }
}
