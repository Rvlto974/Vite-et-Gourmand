<?php

class Language {
    private static $translations = [];
    private static $currentLang = 'fr';
    
    // Initialiser la langue
    public static function init() {
        // Récupérer la langue depuis la session ou le défaut
        if (isset($_SESSION['lang'])) {
            self::$currentLang = $_SESSION['lang'];
        } else {
            self::$currentLang = 'fr';
            $_SESSION['lang'] = 'fr';
        }
        
        // Charger les traductions
        self::loadTranslations(self::$currentLang);
    }
    
    // Charger les traductions d'une langue
    private static function loadTranslations($lang) {
        $file = __DIR__ . '/../lang/' . $lang . '.php';
        
        if (file_exists($file)) {
            self::$translations = require $file;
        } else {
            // Si la langue n'existe pas, charger le français par défaut
            self::$translations = require __DIR__ . '/../lang/fr.php';
        }
    }
    
    // Changer de langue
    public static function setLanguage($lang) {
        if (in_array($lang, ['fr', 'en'])) {
            self::$currentLang = $lang;
            $_SESSION['lang'] = $lang;
            self::loadTranslations($lang);
        }
    }
    
    // Obtenir la langue actuelle
    public static function getCurrentLanguage() {
        return self::$currentLang;
    }
    
    // Traduire une clé
    public static function translate($key, $default = null) {
        if (isset(self::$translations[$key])) {
            return self::$translations[$key];
        }
        
        return $default ?? $key;
    }
}

// Fonction helper globale pour traduire
function __($key, $default = null) {
    return Language::translate($key, $default);
}