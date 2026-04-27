<?php
class controllerAdmin {
    public static function formLoginSite() {
        include_once('viewAdmin/formLogin.php');
    }

    public static function logoutAction() {
        Login::logout();
        include_once('viewAdmin/formLogin.php');
    }
}