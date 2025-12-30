<?php

class Reservation {
    private $conn;
    private $table = 'reservations';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une réservation
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id_utilisateur, id_menu, date_reservation, heure_debut, heure_fin, 
                   nb_personnes, adresse_livraison, commentaire, prix_total, statut) 
                  VALUES (:user_id, :menu_id, :date_resa, :heure_debut, :heure_fin, 
                          :nb_personnes, :adresse, :commentaire, :prix_total, :statut)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['id_utilisateur']);
        $stmt->bindParam(':menu_id', $data['id_menu']);
        $stmt->bindParam(':date_resa', $data['date_reservation']);
        $stmt->bindParam(':heure_debut', $data['heure_debut']);
        $stmt->bindParam(':heure_fin', $data['heure_fin']);
        $stmt->bindParam(':nb_personnes', $data['nb_personnes']);
        $stmt->bindParam(':adresse', $data['adresse_livraison']);
        $stmt->bindParam(':commentaire', $data['commentaire']);
        $stmt->bindParam(':prix_total', $data['prix_total']);
        $stmt->bindParam(':statut', $data['statut']);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    // Récupérer les réservations d'un utilisateur
    public function getByUser($user_id) {
        $query = "SELECT r.*, m.titre as menu_titre, m.prix_base 
                  FROM " . $this->table . " r
                  INNER JOIN menu m ON r.id_menu = m.id_menu
                  WHERE r.id_utilisateur = :user_id
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Récupérer toutes les réservations (admin)
    public function getAll() {
        $query = "SELECT r.*, m.titre as menu_titre, u.nom, u.prenom, u.email 
                  FROM " . $this->table . " r
                  INNER JOIN menu m ON r.id_menu = m.id_menu
                  INNER JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
                  ORDER BY r.date_reservation DESC, r.heure_debut DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Récupérer une réservation par ID
    public function getById($id) {
        $query = "SELECT r.*, m.titre as menu_titre, m.prix_base,
                         u.nom, u.prenom, u.email, u.gsm
                  FROM " . $this->table . " r
                  INNER JOIN menu m ON r.id_menu = m.id_menu
                  INNER JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
                  WHERE r.id_reservation = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    // Vérifier disponibilité d'un créneau
    public function checkAvailability($date, $heure_debut, $heure_fin, $exclude_id = null) {
        $query = "SELECT COUNT(*) as count 
                  FROM " . $this->table . " 
                  WHERE date_reservation = :date 
                  AND statut != 'annulee'
                  AND (
                      (heure_debut < :heure_fin AND heure_fin > :heure_debut)
                  )";
        
        if ($exclude_id) {
            $query .= " AND id_reservation != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':heure_debut', $heure_debut);
        $stmt->bindParam(':heure_fin', $heure_fin);
        
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        // Limite de 5 réservations simultanées par créneau
        return $result['count'] < 5;
    }
    
    // Obtenir les événements pour le calendrier
    public function getEvents() {
        $query = "SELECT r.id_reservation, r.date_reservation, r.heure_debut, r.heure_fin,
                         r.nb_personnes, r.statut, m.titre as menu_titre,
                         u.prenom, u.nom
                  FROM " . $this->table . " r
                  INNER JOIN menu m ON r.id_menu = m.id_menu
                  INNER JOIN utilisateur u ON r.id_utilisateur = u.id_utilisateur
                  WHERE r.statut != 'annulee'
                  ORDER BY r.date_reservation, r.heure_debut";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Mettre à jour le statut
    public function updateStatus($id, $statut) {
        $query = "UPDATE " . $this->table . " SET statut = :statut WHERE id_reservation = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':statut', $statut);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    // Supprimer (annuler) une réservation
    public function cancel($id) {
        return $this->updateStatus($id, 'annulee');
    }
}