<?php
/**
 * File: model/HeroSlider.php
 * Purpose: Handles data retrieval for the hero slider slides from the database.
 */

/**
 * Class HeroSlider
 * 
 * Provides static methods to retrieve slides for the main page hero slider.
 */
class HeroSlider {
    /**
     * Retrieves all slides for the hero slider.
     * 
     * @return array An array of hero slides, each containing its ID and image path.
     */
    public static function getAllHeroSlides() {
        $query = "SELECT hs.id AS slide_id,
                        hs.image AS hero_slide
                    FROM hero_slides hs";
        $db = new Database();
        $arr = $db->getAll($query);
        return is_array($arr) ? $arr : [];
    }
}
?>
