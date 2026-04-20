<?php
class Arts {
    public static function getAllArtsInShop($lang) {
        $query = "SELECT a.id AS art_id,
                        a.price AS art_price,
                        a.in_shop AS in_shop,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND l.code = ? AND a.in_shop = 1
                    ORDER BY a.id DESC";
        $db = new Database();
        $arr = $db->getAll($query, [$lang]);
        return $arr;
    }

    public static function getAllArtsInShopCount($lang) {
        $query = "SELECT COUNT(*) AS total
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND l.code = ? AND a.in_shop = 1";

        $db = new Database();
        $arr = $db->getOne($query, [$lang]);
        return (int)$arr['total'];
    }

    public static function getArtsByCategoryInShop($id, $lang) {
        $query = "SELECT a.id AS art_id,
                        a.price AS art_price,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image,
                        c.id AS category_id
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.in_shop = 1
                    ORDER BY a.id DESC";
        $db = new Database();
        $arr = $db->getAll($query, [$id, $lang]);
        return $arr;
    }

    public static function getArtsCountByCategoryInShop($id, $lang) {
        $query = "SELECT COUNT(*) AS total
                FROM arts a
                JOIN art_lang al ON al.art_id = a.id
                JOIN art_images ai ON ai.art_id = a.id
                JOIN categories c ON a.category_id = c.id 
                JOIN languages l ON al.lang_id = l.id 
                WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.in_shop = 1";

        $db = new Database();
        $arr = $db->getOne($query, [$id, $lang]);

        return (int)$arr['total'];
    }

    public static function getArtsByCategoryID($id, $lang) {
        $query = "SELECT a.id AS art_id,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image,
                        c.id AS category_id
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ?
                    ORDER BY a.id DESC";
        $db = new Database();
        $arr = $db->getAll($query, [$id, $lang]);
        return $arr;
    }

    public static function getArtsCountByCategory($id, $lang) {
        $query = "SELECT COUNT(*) AS total
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id 
                    JOIN languages l ON al.lang_id = l.id 
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ?";

        $db = new Database();
        $arr = $db->getOne($query, [$id, $lang]);

        return (int)$arr['total'];
    }

    public static function getArtById($id, $lang) {
        $query = "SELECT a.id AS art_id,
                        a.category_id AS cat_id,
                        a.price AS art_price,
                        a.in_shop AS art_in_shop, 
                        cl.name AS cat_name,
                        al.title AS art_title,
                        al.text AS art_text,
                        cl.name AS cat_name,  
                        l.code AS lang_code,
                        u.username AS author,
                        u.picture AS author_picture
                    FROM arts a 
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    JOIN categories c ON c.id = a.category_id
                    JOIN cat_lang cl ON cl.cat_id = c.id AND cl.lang_id = l.id
                    JOIN users u ON a.user_id = u.id
                    WHERE a.id = ? AND l.code = ?";
        $db = new Database();
        $n = $db->getOne($query, [$id, $lang]);
        return $n;
    }

    public static function getArtImages($id) {
        $query = "SELECT a.id AS art_id,
                        ai.image AS art_image,
                        ai.position
                    FROM arts a
                    JOIN art_images ai ON ai.art_id = a.id
                    WHERE a.id = ?
                    ORDER BY ai.position ASC";
        $db = new Database();
        $arr = $db->getAll($query, [$id]);
        return $arr;
    }
}
?>