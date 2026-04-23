<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

require 'inc/Database.php';
require 'inc/Lang.php';

require 'model/Arts.php';
require 'model/HeroSlider.php';
require 'model/Category.php';

require 'view/pagination.php';

require 'controller/Controller.php';
require 'route/routing.php';

echo $response;
?>