<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
/**
 * Tests for the Arts model, covering art retrieval functionalities for the shop and individual art details.
 */
#[CoversClass(Arts::class)]
final class ArtsTest extends BaseTestCase
{
    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        Database::reset();
    }

    /**
     * Test that getAllArtsInShop method correctly returns an array of arts available in the shop.
     */
    public function testGetAllArtsInShopReturnsArray(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM arts a') && str_contains($query, 'a.in_shop = 1'),
            [[
                'art_id' => 1,
                'art_price' => 150,
                'art_title' => 'Test',
                'art_image' => 'images/arts/test.jpg',
                'in_shop' => 1,
                'is_deleted' => 0
            ]]
        );

        $result = Arts::getAllArtsInShop('en', 12, 0);

        $this->assertIsArray($result);
        $this->assertSame(1, $result[0]['art_id']);
        $this->assertSame(150, $result[0]['art_price']);
    }

    /**
     * Test that getAllArtsInShopCount method returns the correct count of arts available in the shop.
     */
    public function testGetAllArtsInShopCountReturnsInteger(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'COUNT(*) AS total') && str_contains($query, 'a.in_shop = 1'),
            ['total' => 5]
        );

        $this->assertSame(5, Arts::getAllArtsInShopCount('en'));
    }

    /**
     * Test that getArtById method returns the correct art record for a given ID and language.
     */
    public function testGetArtByIdReturnsOneRecord(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'WHERE a.id = ? AND l.code = ?'),
            [
                'art_id' => 1,
                'art_price' => 150,
                'art_title' => 'Test',
                'art_text' => 'Description',
                'cat_name' => 'Painting',
                'lang_code' => 'en',
                'author' => 'Artist'
            ]
        );

        $result = Arts::getArtById(1, 'en');

        $this->assertSame(1, $result['art_id']);
        $this->assertSame('Painting', $result['cat_name']);
    }

    /**
     * Test that getArtsByIds method uses a placeholder list for the IN clause.
     */
    public function testGetArtsByIdsUsesPlaceholderList(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'a.id IN') && count($params) === 5,
            [
                [
                    'art_id' => 1,
                    'art_price' => 100,
                    'art_title' => 'Art 1',
                    'art_image' => 'images/1.jpg',
                    'category_title' => 'Poster'
                ]
            ]
        );

        $result = Arts::getArtsByIds([1, 2, 3], 'en');

        $this->assertCount(1, $result);
        $this->assertSame('Art 1', $result[0]['art_title']);
    }
}
