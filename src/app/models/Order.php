<?php

class Order {
    private $conn;
    private $table = 'commande';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une commande
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id_utilisateur, id_menu, nb_personnes, prix_menu, prix_livraison, prix_total, 
                   adresse_livraison, date_livraison, heure_livraison, statut) 
                  VALUES 
                  (:id_utilisateur, :id_menu, :nb_personnes, :prix_menu, :prix_livraison, :prix_total,
                   :adresse_livraison, :date_livraison, :heure_livraison, 'en_attente')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id_utilisateur', $data['id_utilisateur']);
        $stmt->bindParam(':id_menu', $data['id_menu']);
        $stmt->bindParam(':nb_personnes', $data['nb_personnes']);
        $stmt->bindParam(':prix_menu', $data['prix_menu']);
        $stmt->bindParam(':prix_livraison', $data['prix_livraison']);
        $stmt->bindParam(':prix_total', $data['prix_total']);
        $stmt->bindParam(':adresse_livraison', $data['adresse_livraison']);
        $stmt->bindParam(':date_livraison', $data['date_livraison']);
        $stmt->bindParam(':heure_livraison', $data['heure_livraison']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    // Récupérer les commandes d'un utilisateur
    public function getByUser($user_id) {
        $query = "SELECT c.*, m.titre AS menu_titre, m.prix_base
                  FROM " . $this->table . " c
                  INNER JOIN menu m ON c.id_menu = m.id_menu
                  WHERE c.id_utilisateur = :user_id
                  ORDER BY c.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer une commande par ID
    public function getById($id) {
        $query = "SELECT c.*, m.titre AS menu_titre, m.prix_base, m.description,
                         u.nom, u.prenom, u.email, u.gsm
                  FROM " . $this->table . " c
                  INNER JOIN menu m ON c.id_menu = m.id_menu
                  INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                  WHERE c.id_commande = :id
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Récupérer toutes les commandes (admin)
    public function getAll() {
        $query = "SELECT c.*, m.titre AS menu_titre, 
                         u.nom, u.prenom, u.email
                  FROM " . $this->table . " c
                  INNER JOIN menu m ON c.id_menu = m.id_menu
                  INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                  ORDER BY c.date_creation DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mettre à jour le statut
    public function updateStatus($id, $new_status) {
        $query = "UPDATE " . $this->table . " 
                  SET statut = :statut 
                  WHERE id_commande = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $new_status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    // Calculer le prix total
    public function calculateTotal($prix_base, $nb_personnes, $nb_min) {
        // Prix de base pour nb_personnes_min
        // Si plus de personnes, on ajuste proportionnellement
        $prix_menu = $prix_base;
        if ($nb_personnes > $nb_min) {
            $prix_menu = ($prix_base / $nb_min) * $nb_personnes;
        }
        
        // Frais de livraison (fixe pour l'instant)
        $prix_livraison = 5.00;
        
        // Total
        $prix_total = $prix_menu + $prix_livraison;
        
        return [
            'prix_menu' => round($prix_menu, 2),
            'prix_livraison' => round($prix_livraison, 2),
            'prix_total' => round($prix_total, 2)
        ];
    }
}