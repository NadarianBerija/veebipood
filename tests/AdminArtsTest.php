<?php

use PHPUnit\Framework\TestCase;

final class AdminArtsTest extends BaseTestCase
{
    protected function setUp(): void
    {
        Database::reset();
        $_SESSION = ['csrf_token' => 'token123'];
        $_POST = ['csrf_token' => 'token123'];
        $_FILES = [];
    }

    public function testGetCategoriesAndAuthorsReturnsData(): void
    {
        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM categories c'),
            [[
                'cat_id' => 1,
                'cat_name' => 'Painting'
            ]]
        );

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM users u') && str_contains($query, 'u.id <> 1'),
            [[
                'author_id' => 2,
                'author_name' => 'Artist'
            ]]
        );

        $data = adminArts::getCategoriesAndAuthors();

        $this->assertArrayHasKey('categories', $data);
        $this->assertArrayHasKey('authors', $data);
        $this->assertSame('Painting', $data['categories'][0]['cat_name']);
    }

    public function testAddArtValidationFailsWithoutTitleAndImages(): void
    {
        $_POST = [
            'save' => '1',
            'csrf_token' => 'token123',
            'category' => 'Painting',
            'author' => 'Artist',
            'price' => '100',
            'title_ee' => '',
            'title_en' => '',
            'title_ru' => ''
        ];

        $result = adminArts::addArt();

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Vähemalt üks pilt', $result[1]);
    }

    public function testDeleteArtReturnsFalseWhenArtNotFound(): void
    {
        $_POST['delete'] = '1';

        Database::onQuery(
            fn($query, $params) => str_contains($query, 'FROM arts a') && str_contains($query, 'WHERE a.id=?'),
            null
        );

        $result = adminArts::deleteArt(999);

        $this->assertFalse($result[0]);
        $this->assertStringContainsString('Artwork not found', $result[1]);
    }
}
