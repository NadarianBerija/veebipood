<?php
/**
 * Admin panel routing logic.
 * Maps request paths to specific administrative controller actions.
 */
$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$parts = explode('/', $path);
$path = end($parts);
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

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
elseif ($path == 'artsList') {
    $response = controllerAdmin::AllArts();
}
elseif ($path == 'users') {
    $response = controllerAdmin::Users();
}
elseif ($path == 'addArt') {
    $response = controllerAdmin::AddArtForm();
}
elseif ($path == 'addArtResult') {
    $response = controllerAdmin::AddArt();
}
elseif ($path == 'editArt' && $id) {
    $response = controllerAdmin::EditArtForm($id);
}
elseif ($path == 'editArtResult' && $id) {
    $response = controllerAdmin::EditArt($id);
}
elseif ($path == 'deleteArt' && $id) {
    $response = controllerAdmin::DeleteArtForm($id);
}
elseif ($path == 'deleteArtResult' && $id) {
    $response = controllerAdmin::DeleteArt($id);
}
elseif ($path == 'toggleDeleteArt') {
    $response = controllerAdmin::ToggleDeleteArt();
}
elseif ($path == 'addUser') {
    $response = controllerAdmin::AddUserForm();
}
elseif ($path == 'addUserResult') {
    $response = controllerAdmin::AddUser();
}
elseif ($path == 'editUser' && $id) {
    $response = controllerAdmin::EditUserForm($id);
}
elseif ($path == 'editUserResult' && $id) {
    $response = controllerAdmin::EditUser($id);
}
elseif ($path == 'toggleDeleteUser') {
    $response = controllerAdmin::ToggleDeleteUser();
}
else {
    $response = controllerAdmin::error404();
}