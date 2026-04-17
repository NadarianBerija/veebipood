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
}
?>