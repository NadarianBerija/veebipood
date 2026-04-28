<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$num = substr_count($host, '/');
$path = explode('/', $host)[$num];

if ($path == 'admin' OR $path == '' OR $path == 'index.php') {
    $response = controllerAdmin::formLoginSite();
}
elseif ($path == 'login') {
    $response = controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    $response = controllerAdmin::logoutAction();
}
elseif ($path == 'heroSlides') {
    $response = controllerAdmin::HeroSlides();
}