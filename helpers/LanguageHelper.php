<?php

class LanguageHelper {
    private static $translations = [];
    private static $currentLang = 'vi';

    public static function init() {
        self::$currentLang = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'vi';
        $langFile = dirname(__DIR__) . '/languages/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        }
    }

    public static function trans($key) {
        $keys = explode('.', $key);
        $value = self::$translations;
    
        foreach ($keys as $k) {
            if (is_array($value) && array_key_exists($k, $value)) {
                $value = $value[$k];
            } else {
                return $key;
            }
        }
    
        return $value;
    }

    public static function getCurrentLang() {
        return self::$currentLang;
    }

    public static function setLanguage($lang) {
        $validLangs = ['en', 'vi'];
        if (in_array($lang, $validLangs)) {
            setcookie('lang', $lang, time() + (86400 * 30), '/');
            self::$currentLang = $lang;
            return true;
        }
        return false;
    }

    public static function getAvailableLanguages() {
        return [
            'vi' => 'Tiếng Việt',
            'en' => 'English'
        ];
    }
} 