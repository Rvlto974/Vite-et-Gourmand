<?php

class Promo {
    private $conn;
    private $table = 'codes_promo';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Vérifier et récupérer un code promo valide
    public function validateCode($code, $montant_commande) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE code = :code 
                  AND actif = 1 
                  AND date_debut <= CURDATE() 
                  AND date_fin >= CURDATE() 
                  AND montant_minimum <= :montant
                  AND (utilisation_max IS NULL OR utilisation_actuelle < utilisation_max)
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':montant', $montant_commande);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Calculer la réduction
    public function calculateDiscount($promo, $montant) {
        if ($promo['type'] === 'pourcentage') {
            return ($montant * $promo['valeur']) / 100;
        } else {
            return min($promo['valeur'], $montant);
        }
    }
    
    // Incrémenter l'utilisation
    public function incrementUsage($id_promo) {
        $query = "UPDATE " . $this->table . " 
                  SET utilisation_actuelle = utilisation_actuelle + 1 
                  WHERE id_promo = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id_promo);
        return $stmt->execute();
    }
    
    // Liste tous les codes (admin)
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY date_creation DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Récupérer un code par ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_promo = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Créer un code promo
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (code, type, valeur, montant_minimum, date_debut, date_fin, utilisation_max, actif) 
                  VALUES (:code, :type, :valeur, :montant_min, :date_debut, :date_fin, :util_max, :actif)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':valeur', $data['valeur']);
        $stmt->bindParam(':montant_min', $data['montant_minimum']);
        $stmt->bindParam(':date_debut', $data['date_debut']);
        $stmt->bindParam(':date_fin', $data['date_fin']);
        $stmt->bindParam(':util_max', $data['utilisation_max']);
        $stmt->bindParam(':actif', $data['actif']);
        
        return $stmt->execute();
    }
    
    // Supprimer un code
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_promo = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}