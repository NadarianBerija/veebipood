<?php
/**
 * Main entry point for the public-facing side of the application.
 * Initializes the session, loads environment variables, and includes core components.
 */
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

require 'inc/Database.php';
require 'inc/Lang.php';

require 'model/Arts.php';
require 'model/HeroSlider.php';
require 'model/Category.php';
require 'model/Order.php';

require 'view/pagination.php';

require 'controller/Controller.php';
require 'route/routing.php';

echo $response;
?>