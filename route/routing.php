<?php
$host = explode('?', $_SERVER['REQUEST_URI'])[0];
$queryString = $_SERVER['QUERY_STRING'] ?? '';
$GLOBALS['query'] = $queryString;
$parts = array_values(array_filter(explode('/', $host)));

$baseFolder = 'vihmart';

if ($parts[0] === $baseFolder) {
    array_shift($parts);
}

$availableLangs = ['ee', 'en', 'ru'];
$lang = $parts[0] ?? 'ee';

if (!in_array($lang, $availableLangs, true)) {
    $lang = 'ee';
    $pathParts = $parts;
} else {
    $pathParts = array_slice($parts, 1);
}

$path = implode('/', $pathParts);

define('APP_LANG', $lang);
define('BASE_URL', '/vihmart');

$GLOBALS['path'] = $path;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($path == '' OR $path == 'index' OR $path == 'index.php') {
    $response = Controller::StartSite();
}
elseif ($path == 'shop') {
    $response = Controller::AllArtsShop();
}
elseif ($path == 'aboutUs') {
    $response = Controller::AboutUs();
}
elseif ($path == 'contact') {
    $response = Controller::Contact();
}
else {
    $response = Controller::error404();
}
?>