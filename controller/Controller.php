<?php
class Controller {
    private static function render($view, $data = []) {
        extract($data);

        ob_start();
        include "views/$view.php";
        $content = ob_get_clean();

        ob_start();
        include "view/layout.php";
        return ob_get_clean();
    }

    public static function StartSite() {
        return self::render('main');
    }
}