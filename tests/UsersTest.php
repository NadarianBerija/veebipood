<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Users helper class, focusing on the cleaning of input data.
 */
#[CoversClass(Users::class)]
final class UsersTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the database and session.
     */
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = ['csrf_token' => 'token123', 'status' => 'admin'];
        $_POST = [];
        $_FILES = [];
    }

    /**
     * Test that the getUserDetail method correctly retrieves user details from the database based on the provided user ID.
     */
    public function testGetUserDetailReturnsUser(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM users u') && str_contains($query, 'WHERE u.id = ?'),
            ['user_id' => 5, 'user_name' => 'Test User', 'user_login' => 'test']
        );

        $result = Users::getUserDetail(5);

        $this->assertSame(5, $result['user_id']);
        $this->assertSame('Test User', $result['user_name']);
    }

    /**
     * Test that the addUser method fails when the provided login already exists in the database, returning the appropriate error message.
     */
    public function testAddUserFailsWhenLoginAlreadyExists(): void
    {
        $_POST = [
            'save' => '1',
            'csrf_token' => 'token123',
            'name' => 'Test',
            'login' => 'testuser',
            'password' => 'password123',
            'confirm' => 'password123',
            'status' => 'admin'
        ];

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT id FROM users WHERE login=?'),
            ['id' => 1]
        );

        $result = Users::addUser();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('See kasutajatunnus on juba kasutusel', $result[1]);
    }

        /**
        * Test that the toggleDeleted method correctly toggles the is_deleted status of a user and their associated arts, returning the new status.
        */
    public function testToggleDeletedReturnsNewStatus(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT u.is_deleted FROM users'),
            ['is_deleted' => 0]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'UPDATE users SET is_deleted = ?'),
            true
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'UPDATE arts SET is_deleted = ?'),
            true
        );

        $result = Users::toggleDeleted(2);

        $this->assertSame(1, $result);
    }
}
