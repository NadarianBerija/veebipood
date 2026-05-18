<?php

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Category model, covering category retrieval functionalities for all categories and individual category details.
 * @covers Category
 */
final class CategoryTest extends BaseTestCase
{
        /**
        * Set up the test environment before each test.
        */
    protected function setUp(): void
    {
        Database::reset();
    }

    /**
     * Test that getAllCategory method correctly returns an array of categories for a given language.
     */
    public function testGetAllCategoryReturnsResults(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM categories c') && $params === ['en'],
            [[
                'category_id' => 10,
                'category_name' => 'Painting',
                'cat_img' => 'images/cat.jpg',
                'language_code' => 'en'
            ]]
        );

        $results = Category::getAllCategory('en');

        $this->assertCount(1, $results);
        $this->assertSame('Painting', $results[0]['category_name']);
    }

    /**
     * Test that getCategoryByID method returns the correct category record for a given ID and language.
     */
    public function testGetCategoryByIdReturnsOneCategory(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'WHERE c.id = ? AND l.code = ?'),
            ['category_id' => 10, 'category_name' => 'Painting']
        );

        $result = Category::getCategoryByID(10, 'en');

        $this->assertSame(10, $result['category_id']);
        $this->assertSame('Painting', $result['category_name']);
    }
}
