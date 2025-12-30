<?php

class FideliteController {
    
    // Page mon programme fidélité
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez être connecté';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $fideliteModel = new Fidelite($conn);
        
        // Récupérer le compte fidélité
        $compte = $fideliteModel->getByUser($_SESSION['user_id']);
        
        // Créer le compte s'il n'existe pas
        if (!$compte) {
            $fideliteModel->create($_SESSION['user_id']);
            $compte = $fideliteModel->getByUser($_SESSION['user_id']);
        }
        
        // Récupérer les avantages du niveau
        $avantages = Fidelite::getAvantages($compte['niveau']);
        
        // Récupérer l'historique
        $historique = $fideliteModel->getHistory($_SESSION['user_id'], 20);
        
        // Calculer progression vers prochain niveau
        $progression = $this->calculateProgression($compte['points_total']);
        
        require_once __DIR__ . '/../views/user/fidelite.php';
    }
    
    // Calculer la progression vers le prochain niveau
    private function calculateProgression($points) {
        $paliers = [
            'bronze' => ['min' => 0, 'max' => 999, 'next' => 'argent', 'next_min' => 1000],
            'argent' => ['min' => 1000, 'max' => 2499, 'next' => 'or', 'next_min' => 2500],
            'or' => ['min' => 2500, 'max' => 4999, 'next' => 'platine', 'next_min' => 5000],
            'platine' => ['min' => 5000, 'max' => 999999, 'next' => null, 'next_min' => null]
        ];
        
        foreach ($paliers as $niveau => $data) {
            if ($points >= $data['min'] && $points <= $data['max']) {
                if ($data['next']) {
                    $remaining = $data['next_min'] - $points;
                    $progress = (($points - $data['min']) / ($data['max'] - $data['min'] + 1)) * 100;
                } else {
                    $remaining = 0;
                    $progress = 100;
                }
                
                return [
                    'niveau_actuel' => $niveau,
                    'prochain_niveau' => $data['next'],
                    'points_restants' => $remaining,
                    'pourcentage' => round($progress, 1)
                ];
            }
        }
        
        return ['niveau_actuel' => 'bronze', 'prochain_niveau' => 'argent', 'points_restants' => 1000 - $points, 'pourcentage' => 0];
    }
}