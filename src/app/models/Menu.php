<?php

class Menu {
    private $conn;
    private $table = 'menu';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer tous les menus actifs
    public function getAll() {
        $query = "SELECT m.*, t.nom_theme, r.nom_regime 
                  FROM " . $this->table . " m
                  LEFT JOIN theme t ON m.id_theme = t.id_theme
                  LEFT JOIN regime r ON m.id_regime = r.id_regime
                  WHERE m.actif = 1
                  ORDER BY m.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un menu par ID
    public function getById($id) {
        $query = "SELECT m.*, t.nom_theme, r.nom_regime 
                  FROM " . $this->table . " m
                  LEFT JOIN theme t ON m.id_theme = t.id_theme
                  LEFT JOIN regime r ON m.id_regime = r.id_regime
                  WHERE m.id_menu = :id AND m.actif = 1
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Récupérer les plats d'un menu
    public function getPlats($menu_id) {
        $query = "SELECT p.* 
                  FROM plat p
                  INNER JOIN menu_plat mp ON p.id_plat = mp.id_plat
                  WHERE mp.id_menu = :menu_id
                  ORDER BY p.type_plat";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer les images d'un menu
    public function getImages($menu_id) {
        $query = "SELECT * FROM image 
                  WHERE id_menu = :menu_id
                  ORDER BY ordre_affichage";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':menu_id', $menu_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Filtrer par thème
    public function getByTheme($theme_id) {
        $query = "SELECT m.*, t.nom_theme, r.nom_regime 
                    FROM " . $this->table . " m
                    LEFT JOIN theme t ON m.id_theme = t.id_theme
                    LEFT JOIN regime r ON m.id_regime = r.id_regime
                    WHERE m.id_theme = :theme_id AND m.actif = 1
                    ORDER BY m.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':theme_id', $theme_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Filtrer par régime
    public function getByRegime($regime_id) {
        $query = "SELECT m.*, t.nom_theme, r.nom_regime 
                    FROM " . $this->table . " m
                    LEFT JOIN theme t ON m.id_theme = t.id_theme
                    LEFT JOIN regime r ON m.id_regime = r.id_regime
                    WHERE m.id_regime = :regime_id AND m.actif = 1
                    ORDER BY m.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':regime_id', $regime_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer tous les thèmes
    public function getAllThemes() {
        $query = "SELECT * FROM theme ORDER BY nom_theme";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer tous les régimes
    public function getAllRegimes() {
        $query = "SELECT * FROM regime ORDER BY nom_regime";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}