<?php
class HeroSlides {
    public static function getAllSlides() {
        $query = "SELECT hs.id AS slide_id,
                         hs.image AS slide_img
                  FROM hero_slides hs";
        $db = new Database();
        return $db->getAll($query);
    }
}