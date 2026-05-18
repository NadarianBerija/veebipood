<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;

/**
 * Integration tests for the PublicController, focusing on page rendering and data retrieval for public-facing pages.
 */
#[CoversClass(Controller::class)]
final class PublicControllerIntegrationTest extends BaseTestCase
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
     * Test that the StartSite method returns HTML content containing the expected elements.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testAllArtsShopPageRendersWithProducts(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT COUNT(*) AS total'),
            ['total' => 1]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'JOIN art_lang al') && str_contains($query, 'LIMIT ? OFFSET ?'),
            [
                [
                    'art_id' => 1,
                    'art_price' => '100',
                    'art_title' => 'Test Art',
                    'art_text' => 'Some text',
                    'art_image' => 'images/test.jpg',
                    'is_deleted' => 0,
                ]
            ]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM categories c') && str_contains($query, 'JOIN cat_lang cl'),
            [
                [
                    'category_id' => 1,
                    'cat_img' => 'cat.jpg',
                    'category_name' => 'Test Category'
                ]
            ]
        );

        $html = Controller::AllArtsShop();

        $this->assertStringContainsString('Shop', $html);
        $this->assertStringContainsString('Test Art', $html);
        $this->assertStringContainsString('Test Category', $html);
    }

    /**
     * Test that the ArtsByCatID method returns HTML content containing the expected category and product details.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testArtsByCatIDPageRendersCategoryProducts(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'SELECT COUNT(*) AS total') && str_contains($query, 'c.id = ?'),
            ['total' => 1]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'JOIN art_lang al') && str_contains($query, 'c.id = ?'),
            [
                [
                    'art_id' => 2,
                    'art_price' => '200',
                    'art_title' => 'Category Art',
                    'art_text' => 'Category text',
                    'art_image' => 'images/cat.jpg',
                    'is_deleted' => 0,
                ]
            ]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM categories c') && str_contains($query, 'WHERE c.id = ?'),
            [
                'category_id' => 2,
                'category_name' => 'Category Name',
                'language_code' => 'en'
            ]
        );

        $html = Controller::ArtsByCatID(2);

        $this->assertStringContainsString('Category Art', $html);
        $this->assertStringContainsString('Category Name', $html);
    }

    /**
     * Test that the HeroSlides method renders the hero slides page with the expected slide data.
     */
    #[RunInSeparateProcess]
    #[PreserveGlobalState(enabled: false)]
    public function testArtByIdPageRendersProductDetails(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'JOIN users u ON a.user_id = u.id') && str_contains($query, 'WHERE a.id = ?'),
            [
                'art_id' => 3,
                'cat_id' => 2,
                'art_price' => '300',
                'art_title' => 'Single Art',
                'art_text' => 'Single art description',
                'cat_name' => 'Category Name',
                'lang_code' => 'en',
                'author' => 'Author Name',
                'author_picture' => 'author.jpg',
            ]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'JOIN art_images ai ON ai.art_id = a.id'),
            [
                ['art_id' => 3, 'art_image' => 'images/single.jpg', 'position' => 0]
            ]
        );

        $html = Controller::ArtByID(3);

        $this->assertStringContainsString('Single Art', $html);
        $this->assertStringContainsString('Author Name', $html);
    }
}
