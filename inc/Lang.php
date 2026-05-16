<?php
/**
 * File: inc/Lang.php
 * Purpose: Manages application localization by loading language files and providing access to translated strings.
 */

/**
 * Class Lang
 * 
 * Provides static methods for loading language data and retrieving translated text based on the active application language.
 */
class Lang {
    /** @var array Holds the loaded localization data as an associative array. */
    private static array $data = [];

    /**
     * Loads a language file based on the current application language.
     * 
     * @param string $file The name of the language file to load (without extension).
     * @return void
     */
    public static function load($file) {
        $allowed = ['ee', 'en', 'ru'];

        // Determine the language to load, defaulting to 'ee' (Estonian) if APP_LANG is not supported.
        $lang = in_array(APP_LANG, $allowed) ? APP_LANG : 'ee';

        $path = __DIR__ . "/../lang/" . $lang . "/$file.php";

        if (file_exists($path)) {
            // Merge the existing data with the data from the newly loaded file.
            self::$data = array_merge(self::$data, require $path);
        }
    }

    /**
     * Retrieves a translated string by its key.
     * 
     * @param string $key The key for the translated string.
     * @return string The translated string if found, otherwise the key itself.
     */
    public static function get(string $key): string {
        return self::$data[$key] ?? $key;
    }
}
