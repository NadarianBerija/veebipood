<?php
require __DIR__ . '/../public/vendor/autoload.php';

if (!defined('APP_LANG')) {
    define('APP_LANG', 'en');
}

if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/TestStubs.php';
require_once __DIR__ . '/BaseTestCase.php';
require_once __DIR__ . '/../inc/Lang.php';
require_once __DIR__ . '/../controller/Controller.php';
require_once __DIR__ . '/../admin/controllerAdmin/controllerAdmin.php';
require_once __DIR__ . '/../admin/modelAdmin/Login.php';
require_once __DIR__ . '/../admin/modelAdmin/adminArts.php';
require_once __DIR__ . '/../admin/modelAdmin/Users.php';
require_once __DIR__ . '/../admin/modelAdmin/HeroSlides.php';
require_once __DIR__ . '/../model/Arts.php';
require_once __DIR__ . '/../model/Category.php';
require_once __DIR__ . '/../model/HeroSlider.php';
require_once __DIR__ . '/../model/Order.php';
