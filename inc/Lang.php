<?php

class Lang {
    private static array $data = [];

    public static function load($file) {
        $allowed = ['ee', 'en', 'ru'];

        $lang = in_array(APP_LANG, $allowed) ? APP_LANG : 'ee';

        $path = __DIR__ . "/../lang/" . $lang . "/$file.php";

        if (file_exists($path)) {
            self::$data = array_merge(self::$data, require $path);
        }
    }

    public static function get(string $key): string {
        return self::$data[$key] ?? $key;
    }
}