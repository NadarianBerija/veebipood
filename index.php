<?php
session_start();

require 'inc/Database.php';
require 'inc/Lang.php';

require 'model/Arts.php';
require 'model/HeroSlider.php';
require 'model/Category.php';

require 'controller/Controller.php';
require 'route/routing.php';

echo $response;
?>