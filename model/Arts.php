<?php
/**
 * File: model/Arts.php
 * Purpose: Handles data retrieval for art pieces from the database.
 */

/**
 * Class Arts
 * 
 * Provides static methods to retrieve art pieces, filtered by various criteria like category, status (in shop), and language.
 */
class Arts {
    /**
     * Retrieves all art pieces available in the shop for a specific language, with pagination.
     * 
     * @param string $lang The language code (e.g., 'en', 'ee', 'ru').
     * @param int $limit The maximum number of records to return.
     * @param int $offset The number of records to skip.
     * @return array An array of art pieces.
     */
    public static function getAllArtsInShop($lang, $limit, $offset) {
        $query = "SELECT a.id AS art_id,
                        a.price AS art_price,
                        a.in_shop AS in_shop,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image,
                        a.is_deleted as is_deleted
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND l.code = ? AND a.in_shop = 1 AND a.is_deleted = 0
                    ORDER BY a.id DESC
                    LIMIT ? OFFSET ?";
        $db = new Database();
        $arr = $db->getAll($query, [$lang, $limit, $offset]);
        return $arr;
    }

    /**
     * Counts the total number of art pieces available in the shop for a specific language.
     * 
     * @param string $lang The language code.
     * @return int The total count of art pieces.
     */
    public static function getAllArtsInShopCount($lang) {
        $query = "SELECT COUNT(*) AS total
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND l.code = ? AND a.in_shop = 1 AND a.is_deleted = 0";

        $db = new Database();
        $arr = $db->getOne($query, [$lang]);
        return (int)($arr['total'] ?? 0);
    }

    /**
     * Retrieves art pieces for a specific category and language available in the shop, with pagination.
     * 
     * @param int $id The category ID.
     * @param string $lang The language code.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The number of records to skip.
     * @return array An array of art pieces.
     */
    public static function getArtsByCategoryInShop($id, $lang, $limit, $offset) {
        $query = "SELECT a.id AS art_id,
                        a.price AS art_price,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image,
                        c.id AS category_id,
                        a.is_deleted as is_deleted
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.in_shop = 1 AND a.is_deleted = 0
                    ORDER BY a.id DESC
                    LIMIT ? OFFSET ?";
        $db = new Database();
        $arr = $db->getAll($query, [$id, $lang, $limit, $offset]);
        return $arr;
    }

    /**
     * Counts the total number of art pieces for a specific category and language in the shop.
     * 
     * @param int $id The category ID.
     * @param string $lang The language code.
     * @return int The total count of art pieces.
     */
    public static function getArtsCountByCategoryInShop($id, $lang) {
        $query = "SELECT COUNT(*) AS total
                FROM arts a
                JOIN art_lang al ON al.art_id = a.id
                JOIN art_images ai ON ai.art_id = a.id
                JOIN categories c ON a.category_id = c.id 
                JOIN languages l ON al.lang_id = l.id 
                WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.in_shop = 1 AND a.is_deleted = 0";

        $db = new Database();
        $arr = $db->getOne($query, [$id, $lang]);

        return (int)($arr['total'] ?? 0);
    }

    /**
     * Retrieves art pieces for a specific category ID and language (ignoring shop status), with pagination.
     * 
     * @param int $id The category ID.
     * @param string $lang The language code.
     * @param int $limit The maximum number of records to return.
     * @param int $offset The number of records to skip.
     * @return array An array of art pieces.
     */
    public static function getArtsByCategoryID($id, $lang, $limit, $offset) {
        $query = "SELECT a.id AS art_id,
                        al.title AS art_title,
                        al.text AS art_text,
                        ai.image AS art_image,
                        c.id AS category_id,
                        a.is_deleted AS is_deleted
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id
                    JOIN languages l ON al.lang_id = l.id
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.is_deleted = 0
                    ORDER BY a.id DESC
                    LIMIT ? OFFSET ?";
        $db = new Database();
        $arr = $db->getAll($query, [$id, $lang, $limit, $offset]);
        return $arr;
    }

    /**
     * Counts the total number of art pieces for a specific category and language (ignoring shop status).
     * 
     * @param int $id The category ID.
     * @param string $lang The language code.
     * @return int The total count of art pieces.
     */
    public static function getArtsCountByCategory($id, $lang) {
        $query = "SELECT COUNT(*) AS total
                    FROM arts a
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN art_images ai ON ai.art_id = a.id
                    JOIN categories c ON a.category_id = c.id 
                    JOIN languages l ON al.lang_id = l.id 
                    WHERE ai.position = 0 AND c.id = ? AND l.code = ? AND a.is_deleted = 0";

        $db = new Database();
        $arr = $db->getOne($query, [$id, $lang]);

        return (int)($arr['total'] ?? 0);
    }

    /**
     * Retrieves a single art piece by its ID and language.
     * 
     * @param int $id The art piece ID.
     * @param string $lang The language code.
     * @return array|false The art piece data as an associative array, or false if not found.
     */
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
                        u.picture AS author_picture,
                        a.is_deleted AS is_deleted
                    FROM arts a 
                    JOIN art_lang al ON al.art_id = a.id
                    JOIN languages l ON al.lang_id = l.id
                    JOIN categories c ON c.id = a.category_id
                    JOIN cat_lang cl ON cl.cat_id = c.id AND cl.lang_id = l.id
                    JOIN users u ON a.user_id = u.id
                    WHERE a.id = ? AND l.code = ? AND a.is_deleted = 0";
        $db = new Database();
        $n = $db->getOne($query, [$id, $lang]);
        return $n;
    }

    /**
     * Retrieves all images associated with a specific art piece ID.
     * 
     * @param int $id The art piece ID.
     * @return array An array of art image data.
     */
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

    /**
     * Retrieves art pieces by a list of their IDs and language.
     * 
     * @param array $ids An array of art piece IDs.
     * @param string $lang The language code.
     * @return array An array of art pieces.
     */
    public static function getArtsByIds($ids, $lang) {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $query = "SELECT a.id AS art_id,
                        a.price AS art_price,
                        al.title AS art_title,
                        ai.image AS art_image,
                        cl.name AS category_title,
                        a.is_deleted AS is_deleted
                FROM arts a
                JOIN art_lang al ON al.art_id = a.id
                JOIN art_images ai ON ai.art_id = a.id
                JOIN languages l ON al.lang_id = l.id
                JOIN categories c ON c.id = a.category_id
                JOIN cat_lang cl ON cl.cat_id = c.id
                JOIN languages cl_lang ON cl.lang_id = cl_lang.id
                WHERE ai.position = 0
                AND l.code = ?
                AND cl_lang.code = ?
                AND a.id IN ($placeholders)
                AND a.is_deleted = 0";

        $db = new Database();
        $arr = $db->getAll($query, array_merge([$lang, $lang], $ids));
        return $arr;
    }

    /**
     * Retrieves all users' profile pictures and usernames.
     * 
     * @return array An array of users, each containing user ID, username, and profile picture path.
     */
    public static function getAuthorPicture() {
        $query = "SELECT u.id AS user_id,
                         u.username AS username,
                         u.picture AS user_pic
                    FROM users u";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }
}
?>
