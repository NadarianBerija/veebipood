<?php
class controllerAdmin {
    public static function formLoginSite() {
        include_once('viewAdmin/formLogin.php');
    }

    public static function loginAction() {
        $login = Login::authentication();
        if (isset($login) and $login == true) {
            include_once('viewAdmin/dashboard.php');
        } else {
            $_SESSION['errorString'] = 'Vale kasutajanimi või parool';
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function logoutAction() {
        Login::logout();
        include_once('viewAdmin/formLogin.php');
    }

    public static function HeroSlides() {
        $arr = HeroSlides::getAllSlides();
        include_once('viewAdmin/heroSlides.php');
    }
    public static function AllArts() {
        $list = adminArts::getAllArts();
        include_once('viewAdmin/artsList.php');
    }
}