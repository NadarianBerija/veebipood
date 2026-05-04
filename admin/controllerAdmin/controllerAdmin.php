<?php
class controllerAdmin {
    public static function formLoginSite() {
        include_once('viewAdmin/formLogin.php');
    }

    public static function loginAction() {
        $login = Login::authentication();
        if (isset($login) and $login == true) {
            include_once('viewAdmin/dashboard.php');
        } else {
            $_SESSION['errorString'] = 'Vale kasutajanimi või parool';
            include_once('viewAdmin/formLogin.php');
        }
    }

    public static function logoutAction() {
        Login::logout();
        include_once('viewAdmin/formLogin.php');
    }

    public static function HeroSlides() {
        
        $result = null;
        
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
        $list = adminArts::getAllArts();
        include_once('viewAdmin/artsList.php');
    }

    public static function Users() {
        $arr = Users::getAllUsers();
        include_once('viewAdmin/users.php');
    }

    public static function AddArtForm() {
        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];
        include_once('viewAdmin/addArt.php');
    }

    public static function AddArt() {
        $result = adminArts::addArt();

        include_once('viewAdmin/addArt.php');
    }

    public static function EditArtForm($id) {
        $id = (int)$id;

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/editArt.php');
    }

    public static function EditArt($id) {
        $id = (int)$id;

        $result = adminArts::editArt($id);

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/editArt.php');
    }

    public static function DeleteArtForm($id) {
        $id = (int)$id;

        $data = adminArts::getCategoriesAndAuthors();

        $categories = $data['categories'];
        $authors = $data['authors'];

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/deleteArt.php');
    }

    public static function DeleteArt($id) {
        $id = (int)$id;

        $result = adminArts::deleteArt($id);

        $artData = adminArts::getArtById($id);

        include_once('viewAdmin/deleteArt.php');
    }

    public static function ToggleDeleteArt() {
        if (!isset($_POST['id'])) {
            echo json_encode(['success' => false]);
            return;
        }

        $id = (int)$_POST['id'];

        $new = adminArts::toggleDeleted($id);

        echo json_encode([
            'success' => true,
            'is_deleted' => $new
        ]);
    }

    public static function error404() {
        include_once('viewAdmin/error404.php');
    }
}