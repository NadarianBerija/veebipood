<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Login class, focusing on authentication and logout functionality.
 */
#[CoversClass(Login::class)]
final class LoginTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the session and POST data.
     */
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            session_start();
        }

        $_SESSION = [];
        $_POST = [];
        $_COOKIE = [];
    }

    /**
     * Test that the authentication method returns false when no POST data is provided, and that login attempts are not incremented.
     */
    public function testAuthenticationReturnsFalseWithoutPost(): void
    {
        $this->assertFalse(Login::authentication());
        $this->assertSame(0, $_SESSION['login_attempts'] ?? 0);
    }

    /**
     * Test that the authentication method returns false when an invalid CSRF token is provided, and that login attempts are incremented.
     */
    public function testAuthenticationInvalidCsrfIncrementsLoginAttempts(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;

        $_POST['btnLogin'] = '1';
        $_POST['csrf_token'] = 'invalid_token';
        $_POST['login'] = 'testuser';
        $_POST['password'] = 'testpassword';

        $this->assertFalse(Login::authentication());
        $this->assertSame(1, $_SESSION['login_attempts']);
        $this->assertGreaterThan(0, $_SESSION['last_attempt_time']);
    }

    /**
     * Test that the logout method clears the session data, effectively logging the user out.
     */
    public function testLogoutClearsSession(): void
    {
        $_SESSION['sessionId'] = 'abc123';
        $_SESSION['userId'] = 1;
        $_SESSION['status'] = 'admin';

        $this->assertNotEmpty($_SESSION);

        Login::logout();

        $this->assertEmpty($_SESSION);
    }

    /**
     * Test that the authentication method returns true with valid credentials, sets the session status to admin, and resets login attempts.
     */
    public function testAuthenticationReturnsTrueWithValidCredentials(): void
    {
        $_SESSION['csrf_token'] = 'token123';
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt_time'] = 0;

        $_POST['btnLogin'] = '1';
        $_POST['csrf_token'] = 'token123';
        $_POST['login'] = 'validuser';
        $_POST['password'] = 'secret123';

        $hash = password_hash('secret123', PASSWORD_DEFAULT);

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT * FROM users WHERE login = ?'),
            ['id' => 1, 'username' => 'Valid', 'status' => 'admin', 'password' => $hash, 'is_deleted' => 0]
        );

        $this->assertTrue(Login::authentication());
        $this->assertSame('admin', $_SESSION['status']);
        $this->assertSame(0, $_SESSION['login_attempts']);
    }
}
