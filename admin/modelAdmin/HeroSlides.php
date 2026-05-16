<?php
/**
 * Model for managing hero slider slides in the admin panel.
 */
class HeroSlides {
    /**
     * Validates CSRF token to prevent cross-site request forgery.
     * @return void
     */
    private static function checkCSRF() {
        if (!isset($_POST['csrf_token']) ||
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRF validation failed');
        }
    }

    /**
     * Retrieves all hero slides from the database.
     * @return array Array of slides with their IDs and image paths.
     */
    public static function getAllSlides() {
        $query = "SELECT hs.id AS slide_id,
                         hs.image AS slide_img
                  FROM hero_slides hs";
        $db = new Database();
        return $db->getAll($query);
    }

    /**
     * Handles the addition of a new slide, including image upload and validation.
     * @return array Result of the operation with 'success' boolean and 'message' string.
     */
    public static function addSlide() {
        self::checkCSRF();

        if (!isset($_FILES['slide_img']) || $_FILES['slide_img']['error'] !== 0) {
            return [
                'success' => false,
                'message' => 'Pildi üleslaadimisel tekkis viga.'
            ];
        }

        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($_FILES['slide_img']['size'] > $maxFileSize) {
            return [
                'success' => false,
                'message' => 'Pildi suurus ei tohi ületada 2 MB.'
            ];
        }

        $fileExtenstion = strtolower(pathinfo($_FILES['slide_img']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'jfif'];

        if (!in_array($fileExtenstion, $allowedExtensions)) {
            return [
                'success' => false,
                'message' => 'Lubatud on ainult JPG, JPEG, PNG ja JFIF failid.'
            ];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['slide_img']['tmp_name']);
        finfo_close($finfo);

        $allowedMime = ['image/jpeg', 'image/png', 'image/jfif'];
        if (!in_array($mime, $allowedMime)) {
            return [
                'success' => false,
                'message' => 'Tuvastatud failitüüp ei ole lubatud.'
            ];
        }

        $uploadDir = dirname(__DIR__, 2) . '/public/images/hero_slider/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtenstion;
        $destPath = $uploadDir . $newFileName;

        if (!move_uploaded_file($_FILES['slide_img']['tmp_name'], $destPath)) {
            return [
                'success' => false,
                'message' => 'Pildi salvestamisel tekkis viga.'
            ];
        }

        $picture = 'images/hero_slider/' . $newFileName;

        $db = new Database();
        $db->executeRun("INSERT INTO hero_slides (`image`) VALUES (?)", [$picture]);
        return [
            'success' => true,
            'message' => 'Pilt edukalt üles laetud.'
        ];
    }

    /**
     * Deletes a slide by its ID and removes the associated image file.
     * @param int $id The ID of the slide to delete.
     * @return array Result of the operation with 'success' boolean and 'message' string.
     */
    public static function deleteSlide($id) {
        self::checkCSRF();
        
        $db = new Database();
        $slide = $db->getOne("SELECT image FROM hero_slides WHERE id = ?", [$id]);

        if (!$slide) {
            return [
                'success' => false,
                'message' => 'Slaidi ei leitud.'
            ];
        }

        $filePaht = dirname(__DIR__, 2) . '/public/' . $slide['image'];
        if (file_exists($filePaht) && is_file($filePaht)) {
            unlink($filePaht);
        }

        $db->executeRun("DELETE FROM hero_slides WHERE id = ?", [$id]);
        return [
            'success' => true,
            'message' => 'Slaid edukalt kustutatud.'
        ];
    }
}