<?php

use PHPUnit\Framework\TestCase;

final class ArtsTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
    }

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

    public function testGetAllArtsInShopCountReturnsInteger(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'COUNT(*) AS total') && str_contains($query, 'a.in_shop = 1'),
            ['total' => 5]
        );

        $this->assertSame(5, Arts::getAllArtsInShopCount('en'));
    }

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
