<?php
class Controller {
    private static function render($view, $data = []) {
        extract($data);

        ob_start();
        include "view/$view.php";
        $content = ob_get_clean();

        ob_start();
        include "view/layout.php";
        return ob_get_clean();
    }

    public static function StartSite() {
        Lang::load('lang');
        return self::render('main');
    }

    public static function AboutUs() {
        Lang::load('lang');
        return self::render('aboutUs');
    }

    public static function Contact() {
        Lang::load('lang');
        return self::render('contact');
    }

    public static function AllHeroSlides() {
        return HeroSlider::getAllHeroSlides();
    }

    public static function AllCategory() {
        return Category::getAllCategory(APP_LANG);
    }

    public static function AllArtsShop() {
        Lang::load('lang');

        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

        if ($categoryId) {
            $allArtsShop = Arts::getArtsByCategoryInShop($categoryId, APP_LANG);
        } else {
            $allArtsShop = Arts::getAllArtsInShop(APP_LANG);
        }

        $categories = Category::getAllCategory(APP_LANG);

        return self::render('allArtsShop', [
            'allArtsShop' => $allArtsShop,
            'categories' => $categories,
            'selectedCategory' => $categoryId
        ]);
    }

    public static function ArtsByCatID($id) {
        Lang::load('lang');

        $arts = Arts::getArtsByCategoryID($id, APP_LANG);
        $category = Category::getCategoryByID($id, APP_LANG);

        return self::render('catArtsGallery', [
            'arts' => $arts,
            'category' => $category
        ]);
    }

    public static function error404() {
        return self::render('error404');
    }
}