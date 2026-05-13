<?php

use PHPUnit\Framework\TestCase;

final class LoginTest extends BaseTestCase
{
    protected function setUp(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        $_POST = [];
        $_COOKIE = [];
    }

    public function testAuthenticationReturnsFalseWithoutPost(): void
    {
        $this->assertFalse(Login::authentication());
        $this->assertSame(0, $_SESSION['login_attempts'] ?? 0);
    }

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

    public function testLogoutClearsSession(): void
    {
        $_SESSION['sessionId'] = 'abc123';
        $_SESSION['userId'] = 1;
        $_SESSION['status'] = 'admin';

        $this->assertNotEmpty($_SESSION);

        Login::logout();

        $this->assertEmpty($_SESSION);
    }

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
