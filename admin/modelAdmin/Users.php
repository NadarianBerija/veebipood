<?php
class Users {
    public static function getAllUsers() {
        $query = "SELECT u.id AS user_id,
                         u.username AS user_name,
                         u.picture AS picture,
                         u.status AS user_status
                  FROM users u";
        $db = new Database();
        return $db->getAll($query);
    }
}