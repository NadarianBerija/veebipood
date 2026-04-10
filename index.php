<?php
session_start();

require 'inc/Database.php';
require 'inc/Lang.php';

require 'controller/Controller.php';
require 'route/routing.php';

echo $response;
?>