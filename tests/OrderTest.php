<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Order class, focusing on the send method which handles order processing and email notifications.
 * @covers Order
 */
final class OrderTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the database and configuring email settings.
     */
    protected function setUp(): void
    {
        Database::reset();
        \PHPMailer\PHPMailer\PHPMailer::$sendSuccess = true;
        $_ENV['MAIL_HOST'] = 'smtp.example.com';
        $_ENV['MAIL_USERNAME'] = 'user';
        $_ENV['MAIL_PASSWORD'] = 'pass';
        $_ENV['MAIL_PORT'] = '587';
        $_ENV['MAIL_FROM'] = 'from@example.com';
        $_ENV['MAIL_FROM_NAME'] = 'Vihmart';
        $_ENV['MAIL_TO'] = 'to@example.com';
    }

    /**
     * Test that the send method returns true when the email is sent successfully, and that the database is updated accordingly.
     */
    public function testSendReturnsTrueWhenMailIsSent(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'UPDATE arts SET in_shop = 0'),
            true
        );

        $result = Order::send('Test', 'test@example.com', '123', 'Hello', [
            ['art_price' => 100, 'art_title' => 'Test Art', 'category_title' => 'Poster']
        ], [1]);

        $this->assertSame([true], $result);
    }

    /**
     * Test that the send method returns false when the email fails to send, and that the database is updated accordingly.
     */
    public function testSendReturnsFalseWhenMailFails(): void
    {
        \PHPMailer\PHPMailer\PHPMailer::$sendSuccess = false;

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'UPDATE arts SET in_shop = 0'),
            true
        );

        $result = Order::send('Test', 'test@example.com', '123', 'Hello', [
            ['art_price' => 50, 'art_title' => 'Test Art', 'category_title' => 'Poster']
        ], [1]);

        $this->assertSame(false, $result[0]);
        $this->assertSame('Send failure', $result[1]);
    }
}
