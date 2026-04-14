<?php
class HeroSlider {
    public static function getAllHeroSlides() {
        $query = "SELECT hs.id AS slide_id,
                        hs.image AS hero_slide
                    FROM hero_slides hs";
        $db = new Database();
        $arr = $db->getAll($query);
        return $arr;
    }
}
?>