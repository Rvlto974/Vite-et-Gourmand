<?php

class LanguageController {
    
    public function switch($lang) {
        // Changer la langue
        Language::setLanguage($lang);
        
        // Rediriger vers la page précédente ou l'accueil
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/';
        header('Location: ' . $redirect);
        exit;
    }
}