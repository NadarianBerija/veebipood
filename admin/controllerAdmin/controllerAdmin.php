<?php
/**
 * File: admin/controllerAdmin/controllerAdmin.php
 * Purpose: Admin controller handling administrative routes, authentication, and content management.
 */

/**
 * Class controllerAdmin
 * 
 * Orchestrates administrative tasks such as user management, art piece moderation, and system settings.
 */
class controllerAdmin {
    /**
     * Verifies if the current user has administrative or moderator access.
     * Also handles session timeout.
     * 
     * @param bool $adminOnly If true, only users with 'admin' status are allowed.
     * @return void
     */
    private static function checkAdminAccess($adminOnly = false) {
        $timeout_duration = 1800; // 30 minutes timeout for admin activity

        if (isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > $timeout_duration) {
                Login::logout();
                header("Location: login");
                exit();
            }
        }    

        $_SESSION['last_activity'] = time();

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

    /**
     * Renders the admin login form.
     * 
     * @return void
     */
    public static function formLoginSite() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include_once('viewAdmin/formLogin.php');
    }

    /**
     * Processes the admin login submission.
     * 
     * @return void
     */
    public static function loginAction() {
        $login = Login::authentication();
        if (isset($login) and $login == true) {
            self::AllArts();
        } else {
            include_once('viewAdmin/formLogin.php');
        }
    }

    /**
     * Processes the admin logout action.
     * 
     * @return void
     */
    public static function logoutAction() {
        self::checkAdminAccess();

        Login::logout();
        include_once('viewAdmin/formLogin.php');
    }

    /**
     * Manages hero slider slides in the admin panel.
     * Handles adding and deleting slides.
     * 
     * @return void
     */
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

    /**
     * Renders the list of all art pieces with filtering options.
     * 
     * @return void
     */
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

    /**
     * Renders the list of all users.
     * 
     * @return void
     */
    public static function Users() {
        self::checkAdminAccess();

        $arr = Users::getAllUsers();
        include_once('viewAdmin/users.php');
    }

    /**
     * Renders the form to add a new art piece.
     * 
     * @return void
     */
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

    /**
     * Processes the submission to add a new art piece.
     * 
     * @return void
     */
    public static function AddArt() {
        self::checkAdminAccess();

        if(empty($_SESSION['csrf_token'])){
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = adminArts::addArt();

        include_once('viewAdmin/addArt.php');
    }

    /**
     * Renders the form to edit an existing art piece.
     * 
     * @param int|string $id The ID of the art piece to edit.
     * @return void
     */
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

    /**
     * Processes the submission to edit an existing art piece.
     * 
     * @param int|string $id The ID of the art piece to edit.
     * @return void
     */
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

    /**
     * Renders the confirmation form to delete an art piece.
     * 
     * @param int|string $id The ID of the art piece to delete.
     * @return void
     */
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

    /**
     * Processes the deletion of an art piece.
     * 
     * @param int|string $id The ID of the art piece to delete.
     * @return void
     */
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

    /**
     * Toggles the 'is_deleted' status of an art piece (AJAX).
     * 
     * @return void
     */
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

    /**
     * Toggles the 'is_deleted' status of a user (AJAX). Admin access required.
     * 
     * @return void
     */
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

    /**
     * Renders the form to add a new user. Admin access required.
     * 
     * @return void
     */
    public static function AddUserForm() {
        self::checkAdminAccess(true);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        include_once('viewAdmin/addUser.php');
    }

    /**
     * Processes the submission to add a new user. Admin access required.
     * 
     * @return void
     */
    public static function AddUser() {
        self::checkAdminAccess(true);

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $result = Users::addUser();

        include_once('viewAdmin/addUser.php');
    }

    /**
     * Renders the form to edit an existing user. Admin access required.
     * 
     * @param int|string $id The ID of the user to edit.
     * @return void
     */
    public static function EditUserForm($id) {
        self::checkAdminAccess(true);

        $id = (int)$id;

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $detail = Users::getUserDetail($id);
        include_once('viewAdmin/editUser.php');
    }

    /**
     * Processes the submission to edit an existing user. Admin access required.
     * 
     * @param int|string $id The ID of the user to edit.
     * @return void
     */
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

    /**
     * Renders the admin 404 error page.
     * 
     * @return void
     */
    public static function error404() {

        http_response_code(404);

        include_once('viewAdmin/error404.php');
    }
}
