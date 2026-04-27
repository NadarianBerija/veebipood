<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$path = explode('/', $host)[$num];

if ($path == 'admin' OR $path == '' OR $path == 'index.php') {
    $response = controllerAdmin::formLoginSite();
}