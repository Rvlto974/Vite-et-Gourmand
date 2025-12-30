<?php

class AnalyticsController {
    
    // Dashboard analytics (admin)
    public function adminDashboard() {
        AdminMiddleware::check();
        
        require_once __DIR__ . '/../models/Analytics.php';
        $analytics = new Analytics();
        
        // Récupérer les statistiques
        $totalActions = $analytics->getTotalActions();
        $topPages = $analytics->getTopPages(10);
        $actionsByType = $analytics->getActionsByType();
        $actionsByDay = $analytics->getActionsByDay(30);
        
        // Préparer les données pour les graphiques
        $chartLabels = [];
        $chartData = [];
        foreach ($actionsByDay as $day) {
            $date = sprintf('%04d-%02d-%02d', 
                $day->_id->year, 
                $day->_id->month, 
                $day->_id->day
            );
            $chartLabels[] = $date;
            $chartData[] = $day->count;
        }
        
        require_once __DIR__ . '/../views/admin/analytics.php';
    }
}