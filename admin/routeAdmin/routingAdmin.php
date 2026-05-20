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
    return controllerAdmin::formLoginSite();
}
elseif ($path == 'login') {
    return controllerAdmin::loginAction();
}
elseif ($path == 'logout') {
    return controllerAdmin::logoutAction();
}
elseif ($path == 'heroSlides') {
    return controllerAdmin::HeroSlides();
}
elseif ($path == 'artsList') {
    return controllerAdmin::AllArts();
}
elseif ($path == 'users') {
    return controllerAdmin::Users();
}
elseif ($path == 'addArt') {
    return controllerAdmin::AddArtForm();
}
elseif ($path == 'addArtResult') {
    return controllerAdmin::AddArt();
}
elseif ($path == 'editArt' && $id) {
    return controllerAdmin::EditArtForm($id);
}
elseif ($path == 'editArtResult' && $id) {
    return controllerAdmin::EditArt($id);
}
elseif ($path == 'deleteArt' && $id) {
    return controllerAdmin::DeleteArtForm($id);
}
elseif ($path == 'deleteArtResult' && $id) {
    return controllerAdmin::DeleteArt($id);
}
elseif ($path == 'toggleDeleteArt') {
    return controllerAdmin::ToggleDeleteArt();
}
elseif ($path == 'addUser') {
    return controllerAdmin::AddUserForm();
}
elseif ($path == 'addUserResult') {
    return controllerAdmin::AddUser();
}
elseif ($path == 'editUser' && $id) {
    return controllerAdmin::EditUserForm($id);
}
elseif ($path == 'editUserResult' && $id) {
    return controllerAdmin::EditUser($id);
}
elseif ($path == 'toggleDeleteUser') {
    return controllerAdmin::ToggleDeleteUser();
}
else {
    return controllerAdmin::error404();
}