<?php

class AnalyticsMiddleware {
    
    public static function track() {
        // Ne pas tracker les assets statiques
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $excludedPaths = ['/uploads/', '/css/', '/js/', '/favicon.ico'];
        
        foreach ($excludedPaths as $path) {
            if (strpos($uri, $path) !== false) {
                return;
            }
        }
        
        // Logger la page vue
        require_once __DIR__ . '/../models/Analytics.php';
        $analytics = new Analytics();
        $analytics->logAction('page_view');
    }
}