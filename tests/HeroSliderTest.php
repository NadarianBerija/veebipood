<?php

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the HeroSlider class, focusing on the retrieval of hero slide data from the database.
 * @covers HeroSlider
 */
final class HeroSliderTest extends BaseTestCase
{
        /**
        * Set up the test environment before each test, resetting the database.
        */
    protected function setUp(): void
    {
        Database::reset();
    }

    /**
     * Test that getAllHeroSlides method correctly returns an array of hero slides from the database.
     */
    public function testGetAllHeroSlidesReturnsSlides(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM hero_slides hs'),
            [[
                'slide_id' => 5,
                'slide_img' => 'images/hero_slider/slide.jpg'
            ]]
        );

        $slides = HeroSlider::getAllHeroSlides();

        $this->assertCount(1, $slides);
        $this->assertSame(5, $slides[0]['slide_id']);
    }
}
