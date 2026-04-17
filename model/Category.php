<?php
class Category {
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
        return $arr;
    }

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