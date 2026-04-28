<?php
class adminArts {
    public static function getAllArts() {
        $query = "SELECT a.id AS art_id,
                         al.title AS art_title,
                         u.username AS author,
                         cl.name AS cat_name,
                         a.in_shop,
                         ai.image AS art_image
                  FROM arts a
                  JOIN users u ON a.user_id = u.id
                  LEFT JOIN art_lang al
                    ON al.art_id = a.id
                    AND al.lang_id = (
                        SELECT id FROM languages WHERE code = 'ee' LIMIT 1
                    )
                  LEFT JOIN cat_lang cl
                    ON cl.cat_id = a.category_id
                    AND cl.lang_id = (
                        SELECT id FROM languages WHERE code = 'ee' LIMIT 1
                    )
                  LEFT JOIN art_images ai 
                    ON ai.art_id = a.id
                    AND ai.position = 0
                  ORDER BY a.id DESC";
                  
        $db = new Database();
        return $db->getAll($query);
    }
}