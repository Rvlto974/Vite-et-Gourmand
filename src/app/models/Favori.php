<?php

class Favori {
    private $conn;
    private $table = 'favoris';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Ajouter un favori
    public function add($user_id, $menu_id) {
        $query = "INSERT INTO " . $this->table . " (id_utilisateur, id_menu) VALUES (:user_id, :menu_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        return $stmt->execute();
    }
    
    // Supprimer un favori
    public function remove($user_id, $menu_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_utilisateur = :user_id AND id_menu = :menu_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        return $stmt->execute();
    }
    
    // Vérifier si un menu est en favori pour un utilisateur
    public function isFavorite($user_id, $menu_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE id_utilisateur = :user_id AND id_menu = :menu_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }
    
    // Obtenir tous les favoris d'un utilisateur
    public function getByUser($user_id) {
        $query = "SELECT m.*, f.date_ajout, 
                         t.nom_theme, r.nom_regime,
                         (SELECT chemin_fichier FROM image_menu WHERE id_menu = m.id_menu LIMIT 1) as image
                  FROM " . $this->table . " f
                  INNER JOIN menu m ON f.id_menu = m.id_menu
                  LEFT JOIN theme t ON m.id_theme = t.id_theme
                  LEFT JOIN regime r ON m.id_regime = r.id_regime
                  WHERE f.id_utilisateur = :user_id
                  ORDER BY f.date_ajout DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Compter le nombre de favoris pour un menu
    public function countForMenu($menu_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE id_menu = :menu_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    // Obtenir les menus les plus favorisés
    public function getTopFavorites($limit = 5) {
        $query = "SELECT m.id_menu, m.titre, COUNT(f.id_favori) as nb_favoris
                FROM menu m
                LFT JOIN " . $this->table . " f ON m.id_menu = f.id_menu
                GROUP BY m.id_menu
                HAVING nb_favoris > 0
                ORDER BY nb_favoris DESC
                LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}