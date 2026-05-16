<?php
/**
 * File: model/Category.php
 * Purpose: Handles data retrieval for art categories from the database.
 */

/**
 * Class Category
 * 
 * Provides static methods to retrieve art categories and their localized names.
 */
class Category {
    /**
     * Retrieves all art categories for a specific language.
     * 
     * @param string $lang The language code (e.g., 'en', 'ee', 'ru').
     * @return array An array of categories, each as an associative array.
     */
    public static function getAllCategory($lang) {
        $query = "SELECT c.id AS category_id,
                        c.cat_img AS cat_img,
                        cl.name AS category_name,
                        l.code AS language_code
                    FROM categories c
                    JOIN cat_lang cl ON cl.cat_id = c.id
                    JOIN languages l ON cl.lang_id = l.id
                    WHERE l.code = ?";
        $db = new Database();
        $arr = $db->getAll($query, [$lang]);
        return is_array($arr) ? $arr : [];
    }

    /**
     * Retrieves a single art category by its ID and language.
     * 
     * @param int $id The category ID.
     * @param string $lang The language code.
     * @return array|false The category data as an associative array, or false if not found.
     */
    public static function getCategoryByID($id, $lang) {
        $query = "SELECT c.id AS category_id,
                        cl.name AS category_name,
                        l.code AS language_code
                    FROM categories c
                    JOIN cat_lang cl ON cl.cat_id = c.id
                    JOIN languages l ON cl.lang_id = l.id
                    WHERE c.id = ? AND l.code = ?";
        $db = new Database();
        $n = $db->getOne($query, [$id, $lang]);
        return $n;
    }
}
?>
