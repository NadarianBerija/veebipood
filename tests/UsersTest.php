<?php

use PHPUnit\Framework\TestCase;

final class UsersTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = ['csrf_token' => 'token123', 'status' => 'admin'];
        $_POST = [];
        $_FILES = [];
    }

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
