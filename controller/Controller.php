<?php
/**
 * File: controller/Controller.php
 * Purpose: Main application controller handling public routes and view rendering.
 */

/**
 * Class Controller
 * 
 * Orchestrates the business logic for the public-facing part of the website, 
 * including page rendering, art displays, and shopping cart functionality.
 */
class Controller {
    /**
     * Renders a view within the main layout.
     * 
     * @param string $view The name of the view file (without extension).
     * @param array $data An associative array of data to be extracted into the view.
     * @return string The rendered HTML content.
     */
    private static function render($view, $data = []) {
        extract($data);

        ob_start();
        include "view/$view.php";
        $content = ob_get_clean();

        ob_start();
        include "view/layout.php";
        return ob_get_clean();
    }

    /**
     * Renders the main home page.
     * 
     * @return string The rendered HTML content.
     */
    public static function StartSite() {
        Lang::load('lang');
        return self::render('main');
    }

    /**
     * Renders the "About Us" page.
     * 
     * @return string The rendered HTML content.
     */
    public static function AboutUs() {
        Lang::load('lang');

        $authPic = Arts::getAuthorPicture();

        return self::render('aboutUs',[
            'authPic' => $authPic
        ]);
    }

    /**
     * Renders the "Contact" page.
     * 
     * @return string The rendered HTML content.
     */
    public static function Contact() {
        Lang::load('lang');
        return self::render('contact');
    }

    /**
     * Retrieves all hero slider slides.
     * 
     * @return array An array of hero slides data.
     */
    public static function AllHeroSlides() {
        return HeroSlider::getAllHeroSlides();
    }

    /**
     * Retrieves all art categories.
     * 
     * @return array An array of category data.
     */
    public static function AllCategory() {
        return Category::getAllCategory(APP_LANG);
    }

    /**
     * Renders the main shop page with all arts or filtered by category, including pagination.
     * 
     * @return string The rendered HTML content.
     */
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

    /**
     * Renders arts filtered by a specific category ID in the gallery view.
     * 
     * @param int $id The category ID.
     * @return string The rendered HTML content.
     */
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

    /**
     * Renders the details of a specific art piece.
     * 
     * @param int $id The art piece ID.
     * @param string $type The view type ('shop' or 'gallery'). Defaults to 'shop'.
     * @return string The rendered HTML content.
     */
    public static function ArtByID($id, $type = 'shop') {
        Lang::load('lang');

        $currentArt = Arts::getArtById($id, APP_LANG);

        if (!$currentArt) {
            http_response_code(404);
            return self::render('error404');
        }

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

    /**
     * Renders a 404 error page.
     * 
     * @return string The rendered HTML content.
     */
    public static function error404() {

        http_response_code(404);
        
        return self::render('error404');
    }

    /**
     * Renders the shopping cart page.
     * 
     * @return string The rendered HTML content.
     */
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

    /**
     * Adds an art piece to the shopping cart.
     * 
     * @param int $id The art piece ID.
     * @return void
     */
    public static function CartAdd($id) {
        $_SESSION['cart'][$id] = 1;

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    /**
     * Removes an art piece from the shopping cart.
     * 
     * @param int $id The art piece ID.
     * @return void
     */
    public static function CartRemove($id) {
        unset($_SESSION['cart'][$id]);

        header("Location: " . BASE_URL . '/' . APP_LANG . '/cart');
        exit;
    }

    /**
     * Processes the shopping cart order submission.
     * 
     * @return string The rendered HTML content (cart page with result).
     */
    public static function CartOrder() {

        Lang::load('lang');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            return self::render('cart', [
                'result' => [false, htmlspecialchars(Lang::get('invalid_request'))]
            ]);
        }

        // honeypot check to prevent spam
        if (!empty($_POST['website'])) {

            return self::render('cart', [
                'result' => [false, htmlspecialchars(Lang::get('spam'))]
            ]);
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email) {

            return self::render('cart', [
                'result' => [false, htmlspecialchars(Lang::get('required_fields'))]
            ]);
        }

        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {

            return self::render('cart', [
                'result' => [false, htmlspecialchars(Lang::get('empty_cart'))]
            ]);
        }

        $ids = array_keys($cart);

        $items = Arts::getArtsByIds($ids, 'ee');

        $result = Order::send(
            $name,
            $email,
            $phone,
            $message,
            $items,
            $ids
        );

        if ($result[0]) {
            unset($_SESSION['cart']);
        }

        return self::render('cart', [
            'result' => $result
        ]);
    }
}
