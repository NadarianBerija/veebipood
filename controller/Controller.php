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

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 12;
        $offset = ($page - 1) * $limit;

        $categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;

        if ($categoryId) {
            $allArtsShop = Arts::getArtsByCategoryInShop($categoryId, APP_LANG, $limit, $offset);
            $totalArts = Arts::getArtsCountByCategoryInShop($categoryId, APP_LANG);
        } else {
            $allArtsShop = Arts::getAllArtsInShop(APP_LANG, $limit, $offset);
            $totalArts = Arts::getAllArtsInShopCount(APP_LANG);
        }

        $totalPages = ceil($totalArts / $limit);
        $categories = Category::getAllCategory(APP_LANG);

        return self::render('allArtsShop', [
            'allArtsShop' => $allArtsShop,
            'page' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'selectedCategory' => $categoryId
        ]);
    }

    public static function ArtsByCatID($id) {
        Lang::load('lang');

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 12;
        $offset = ($page - 1) * $limit;

        $arts = Arts::getArtsByCategoryID($id, APP_LANG, $limit, $offset);
        $category = Category::getCategoryByID($id, APP_LANG);
        $totalArts = Arts::getArtsCountByCategory($id, APP_LANG);
        $totalPages = ceil($totalArts / $limit);

        return self::render('catArtsGallery', [
            'arts' => $arts,
            'category' => $category,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public static function ArtByID($id, $type = 'shop') {
        Lang::load('lang');

        $currentArt = Arts::getArtById($id, APP_LANG);
        $images = Arts::getArtImages($id);

        if ($type === 'gallery') {
            return self::render('artGallery', [
                'currentArt' => $currentArt,
                'images' => $images
            ]);
        } else {
            return self::render('artShop', [
                'currentArt' => $currentArt,
                'images' => $images
            ]);
        }
        
    }

    public static function Order($id) {
        Lang::load('lang');

        $art = Arts::getArtById($id, APP_LANG);

        return self::render('order', [
            'art' => $art
        ]);
    }

    public static function error404() {
        return self::render('error404');
    }

    public static function Cart() {
        Lang::load('lang');

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            return self::render('cart', [
                'items' => [],
                'total' => 0
            ]);
        }

        $ids = array_keys($cart);
        $items = Arts::getArtsByIds($ids, APP_LANG);

        $total = 0;

        foreach ($items as &$item) {
            $item['qty'] = 1;
            $total += (float)$item['art_price'];
        }

        return self::render('cart', [
            'items' => $items,
            'total' => $total
        ]);
    }

    public static function CartAdd($id) {
        $_SESSION['cart'][$id] = 1;

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public static function CartRemove($id) {
        unset($_SESSION['cart'][$id]);

        header("Location: " . BASE_URL . '/' . APP_LANG . '/cart');
        exit;
    }
}