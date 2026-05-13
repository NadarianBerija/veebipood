<?php

use PHPUnit\Framework\TestCase;

final class HeroSliderTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
    }

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
