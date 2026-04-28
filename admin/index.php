<?php
session_start();

require '../inc/Database.php';

require 'modelAdmin/Login.php';
require 'modelAdmin/adminArts.php';
require 'modelAdmin/HeroSlides.php';
require 'modelAdmin/Users.php';

require 'controllerAdmin/controllerAdmin.php';
require 'routeAdmin/routingAdmin.php';

echo $response;
?>