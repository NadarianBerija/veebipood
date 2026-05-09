<?php
class controllerAdmin {
    private static function checkAdminAccess($adminOnly = false) {
        if (
            !isset($_SESSION['sessionId']) ||
            !isset($_SESSION['status']) ||
            !in_array($_SESSION['status'], ['admin', 'moderaator'])
        ) {
            http_response_code(403);
            die('Access denied');
        }

        if ($adminOnly && $_SESSION['status'] !== 'admin') {
            http_response_code(403);
            die('Admin access required');
        }
    }

    public static function formLoginSite() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include_once('viewAdmin/formLogin.php');
    }

    public static function loginAction() {
        $login = Login::authentication();
        if (isset($login) and $login == true) {
            self::AllArts();
        } else {
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function logoutAction() {
        self::checkAdminAccess();

        Login::logout();
        include_once('viewAdmin/formLogin.php');
    }

    public static function HeroSlides() {
        self::checkAdminAccess();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        if (isset($_POST['save'])) {

            $result = HeroSlides::addSlide();
            
            $_SESSION['flash'] = $result['message'];
            header("Location: heroSlides");
            exit();
        }

        if (isset($_POST['delete']) && !empty($_POST['slide_id'])) {
            $result = HeroSlides::deleteSlide((int)$_POST['slide_id']);
            $_SESSION['flash'] = $result['message'];
            header("Location: heroSlides");
            exit();
        }

        $arr = HeroSlides::getAllSlides();
        include_once('viewAdmin/heroSlides.php');
    }

    public static function AllArts() {
        self::checkAdminAccess();

        $filters = [
            'author'   => $_GET['author'] ?? null,
            'category' => $_GET['category'] ?? null,
            'in_shop'  => $_GET['in_shop'] ?? null,
            'is_deleted' => $_GET['is_deleted'] ?? null
        ];

        $list = adminArts::getAllArts($filters);
        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];
        include_once('viewAdmin/artsList.php');
    }

    public static function Users() {
        self::checkAdminAccess();

        $arr = Users::getAllUsers();
        include_once('viewAdmin/users.php');
    }

    public static function AddArtForm() {
        self::checkAdminAccess();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];
        include_once('viewAdmin/addArt.php');
    }

    public static function AddArt() {
        self::checkAdminAccess();

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = adminArts::addArt();

        include_once('viewAdmin/addArt.php');
    }

    public static function EditArtForm($id) {
        self::checkAdminAccess();

        $id = (int)$id;

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/editArt.php');
    }

    public static function EditArt($id) {
        self::checkAdminAccess();

        $id = (int)$id;

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = adminArts::editArt($id);

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/editArt.php');
    }

    public static function DeleteArtForm($id) {
        self::checkAdminAccess();

        $id = (int)$id;

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/deleteArt.php');
    }

    public static function DeleteArt($id) {
        self::checkAdminAccess();

        $id = (int)$id;

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = adminArts::deleteArt($id);

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/deleteArt.php');
    }

    public static function ToggleDeleteArt() {
        self::checkAdminAccess();



        if (!isset($_POST['id'])) {
            echo json_encode(['success' => false]);
            return;
        }

        $id = (int)$_POST['id'];

        $result  = adminArts::toggleDeleted($id);

        echo json_encode($result);
    }

    public static function ToggleDeleteUser() {
        self::checkAdminAccess(true);


        if (!isset($_POST['id'])) {
            echo json_encode(['success' => false]);
            return;
        }

        $id = (int)$_POST['id'];

        $user = Users::getUserDetail($id);

        if (!$user) {

            echo json_encode([
                'success' => false,
                'message' => 'Kasutajat ei leitud'
            ]);

            return;
        }

        if ($user['user_status'] === 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'Admin-kasutajat ei saa kustutada'
            ]);
            return;
        }

        $new = Users::toggleDeleted($id);

        echo json_encode([
            'success' => true,
            'is_deleted' => $new
        ]);
    }

    public static function AddUserForm() {
        self::checkAdminAccess(true);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include_once('viewAdmin/addUser.php');
    }

    public static function AddUser() {
        self::checkAdminAccess(true);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = Users::addUser();

        include_once('viewAdmin/addUser.php');
    }

    public static function EditUserForm($id) {
        self::checkAdminAccess(true);

        $id = (int)$id;

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $detail = Users::getUserDetail($id);
        include_once('viewAdmin/editUser.php');
    }

    public static function EditUser($id) {
        self::checkAdminAccess(true);

        $id = (int)$id;

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $detail = Users::getUserDetail($id);

        if (!$detail) {
            die('Kasutajat ei leitud');
        }

        $result = Users::editUser($id);
        include_once('viewAdmin/editUser.php');
    }

    public static function error404() {

        http_response_code(404);

        include_once('viewAdmin/error404.php');
    }
}