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
        return self::render('main');
    }

    public static function AllHeroSlides() {
        return HeroSlider::getAllHeroSlides();
    }

    public static function AllCategory() {
        return Category::getAllCategory();
    }

    public static function error404() {
        return self::render('error404');
    }
}