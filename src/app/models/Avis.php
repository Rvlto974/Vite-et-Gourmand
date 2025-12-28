<?php

class Avis {
    private $conn;
    private $table = 'avis';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un avis
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                    (id_utilisateur, id_commande, note, commentaire, valide) 
                    VALUES (:id_utilisateur, :id_commande, :note, :commentaire, 0)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_utilisateur', $data['id_utilisateur']);
        $stmt->bindParam(':id_commande', $data['id_commande']);
        $stmt->bindParam(':note', $data['note']);
        $stmt->bindParam(':commentaire', $data['commentaire']);
        
        return $stmt->execute();
    }

    // Récupérer les avis validés d'un menu
    public function getByMenu($menu_id) {
        $query = "SELECT a.*, u.prenom, u.nom, c.id_menu
                    FROM " . $this->table . " a
                    INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                    INNER JOIN commande c ON a.id_commande = c.id_commande
                    WHERE c.id_menu = :menu_id AND a.valide = 1
                    ORDER BY a.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer tous les avis (admin)
    public function getAll() {
        $query = "SELECT a.*, u.prenom, u.nom, u.email, m.titre as menu_titre, c.id_menu
                    FROM " . $this->table . " a
                    INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                    INNER JOIN commande c ON a.id_commande = c.id_commande
                    INNER JOIN menu m ON c.id_menu = m.id_menu
                    ORDER BY a.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un avis par ID
    public function getById($id) {
        $query = "SELECT a.*, u.prenom, u.nom, u.email, m.titre as menu_titre
                    FROM " . $this->table . " a
                    INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                    INNER JOIN commande c ON a.id_commande = c.id_commande
                    INNER JOIN menu m ON c.id_menu = m.id_menu
                    WHERE a.id_avis = :id
                    LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Vérifier si l'utilisateur a déjà laissé un avis pour ce menu
    public function userHasReviewed($user_id, $menu_id) {
        $query = "SELECT COUNT(*) as count 
                    FROM " . $this->table . " a
                    INNER JOIN commande c ON a.id_commande = c.id_commande
                    WHERE a.id_utilisateur = :user_id AND c.id_menu = :menu_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    // Vérifier si l'utilisateur a commandé ce menu et que c'est livré
    public function userCanReview($user_id, $menu_id) {
        $query = "  SELECT COUNT(*) as count 
                    FROM commande 
                    WHERE id_utilisateur = :user_id 
                    AND id_menu = :menu_id 
                    AND statut IN ('livre', 'terminee')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    // Récupérer une commande livrée pour ce menu et cet utilisateur
    public function getDeliveredOrder($user_id, $menu_id) {
        $query = "SELECT id_commande 
                    FROM commande 
                    WHERE id_utilisateur = :user_id 
                    AND id_menu = :menu_id 
                    AND statut IN ('livre', 'terminee')
                    LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['id_commande'] : null;
    }

    // Changer le statut d'un avis (valide = 0 ou 1)
    public function updateStatus($id, $status) {
        $valide = ($status === 'valide') ? 1 : 0;
        
        $query = "UPDATE " . $this->table . " 
                    SET valide = :valide 
                    WHERE id_avis = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valide', $valide);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Supprimer un avis
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_avis = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Calculer la note moyenne d'un menu
    public function getAverageRating($menu_id) {
        $query = "SELECT AVG(a.note) as moyenne, COUNT(*) as total 
                    FROM " . $this->table . " a
                    INNER JOIN commande c ON a.id_commande = c.id_commande
                    WHERE c.id_menu = :menu_id AND a.valide = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        return $stmt->fetch();
    }
}