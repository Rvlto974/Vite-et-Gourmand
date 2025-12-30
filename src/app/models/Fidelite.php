<?php

class Fidelite {
    private $conn;
    private $table = 'fidelite';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer un compte fidélité pour un nouvel utilisateur
    public function create($user_id) {
        $query = "INSERT INTO " . $this->table . " (id_utilisateur) VALUES (:user_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }
    
    // Récupérer le compte fidélité d'un utilisateur
    public function getByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_utilisateur = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Ajouter des points
    public function addPoints($user_id, $points, $description, $order_id = null) {
        // Créer le compte s'il n'existe pas
        $compte = $this->getByUser($user_id);
        if (!$compte) {
            $this->create($user_id);
            $compte = $this->getByUser($user_id);
        }
        
        // Ajouter les points
        $new_total = $compte['points_total'] + $points;
        $new_niveau = $this->calculateNiveau($new_total);
        
        $query = "UPDATE " . $this->table . " 
                SET points_total = :points, niveau = :niveau 
                WHERE id_utilisateur = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':points', $new_total);
        $stmt->bindParam(':niveau', $new_niveau);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        // Enregistrer dans l'historique
        $this->addHistory($user_id, $points, 'gain', $description, $order_id);
        
        return ['points' => $new_total, 'niveau' => $new_niveau];
    }
    
    // Calculer le niveau selon les points
    private function calculateNiveau($points) {
        if ($points >= 5000) return 'platine';
        if ($points >= 2500) return 'or';
        if ($points >= 1000) return 'argent';
        return 'bronze';
    }
    
    // Obtenir les avantages du niveau
    public static function getAvantages($niveau) {
        $avantages = [
            'bronze' => [
                'nom' => 'Bronze',
                'icon' => '🥉',
                'reduction' => 5,
                'livraison_gratuite' => false,
                'priorite' => false,
                'acces_exclusif' => false
            ],
            'argent' => [
                'nom' => 'Argent',
                'icon' => '🥈',
                'reduction' => 10,
                'livraison_gratuite' => true,
                'priorite' => false,
                'acces_exclusif' => false
            ],
            'or' => [
                'nom' => 'Or',
                'icon' => '🥇',
                'reduction' => 15,
                'livraison_gratuite' => true,
                'priorite' => true,
                'acces_exclusif' => false
            ],
            'platine' => [
                'nom' => 'Platine',
                'icon' => '💎',
                'reduction' => 20,
                'livraison_gratuite' => true,
                'priorite' => true,
                'acces_exclusif' => true
            ]
        ];
        
        return $avantages[$niveau] ?? $avantages['bronze'];
    }
    
    // Ajouter dans l'historique
    private function addHistory($user_id, $points, $type, $description, $order_id = null) {
        $query = "INSERT INTO historique_points (id_utilisateur, points, type, description, id_commande) 
                    VALUES (:user_id, :points, :type, :description, :order_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':order_id', $order_id);
        return $stmt->execute();
    }
    
    // Obtenir l'historique des points
    public function getHistory($user_id, $limit = 20) {
        $query = "SELECT h.*, c.prix_total 
                    FROM historique_points h
                    LEFT JOIN commande c ON h.id_commande = c.id_commande
                    WHERE h.id_utilisateur = :user_id
                    ORDER BY h.date_creation DESC
                    LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Calculer les points pour un montant
    public static function calculatePoints($montant) {
        return floor($montant * 10); // 1€ = 10 points
    }
}