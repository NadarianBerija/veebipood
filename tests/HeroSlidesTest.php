<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the HeroSlides class, focusing on the addition and deletion of hero slides.
 * @covers HeroSlides
 */
final class HeroSlidesTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test, resetting the database and session.
     */
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = ['csrf_token' => 'token123'];
        $_POST = ['csrf_token' => 'token123'];
        $_FILES = [];
    }

    /**
     * Test that addSlide method fails when no file is uploaded, returning the appropriate error message.
     */
    public function testAddSlideFailsWhenNoFileUploaded(): void
    {
        $_POST['csrf_token'] = 'token123';

        $result = HeroSlides::addSlide();

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Pildi üleslaadimisel tekkis viga', $result['message']);
    }

    /**
     * Test that deleteSlide method successfully deletes a slide and its associated image file, returning the appropriate success message.
     */
    public function testDeleteSlideRemovesImage(): void
    {
        $directory = __DIR__ . '/../public/images/hero_slider/';
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = $directory . 'test-slide.jpg';
        file_put_contents($filename, 'data');

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT image FROM hero_slides'),
            ['image' => 'images/hero_slider/test-slide.jpg']
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'DELETE FROM hero_slides WHERE id = ?'),
            true
        );

        $result = HeroSlides::deleteSlide(1);

        $this->assertTrue($result['success']);
        $this->assertSame('Slaid edukalt kustutatud.', $result['message']);
        $this->assertFileDoesNotExist($filename);
    }
}
