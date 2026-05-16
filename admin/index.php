<?php
/**
 * Entry point for the administrative panel.
 * Sets up session security parameters, loads environment variables, and initializes admin components.
 */
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require '../inc/Database.php';

require 'modelAdmin/Login.php';
require 'modelAdmin/adminArts.php';
require 'modelAdmin/HeroSlides.php';
require 'modelAdmin/Users.php';

require 'controllerAdmin/controllerAdmin.php';
require 'routeAdmin/routingAdmin.php';

echo $response;
?>