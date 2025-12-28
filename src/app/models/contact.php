<?php

class Contact {
    private $conn;
    private $table = 'contact';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer un message de contact
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                    (email, titre, message) 
                    VALUES (:email, :titre, :message)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':message', $data['message']);
        
        return $stmt->execute();
    }

    // Récupérer tous les messages (admin)
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " 
                    ORDER BY date_envoi DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un message par ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " 
                    WHERE id_contact = :id 
                    LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Marquer comme traité
    public function markAsTreated($id) {
        $query = "UPDATE " . $this->table . " 
                    SET traite = 1 
                    WHERE id_contact = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Marquer comme non traité
    public function markAsUntreated($id) {
        $query = "UPDATE " . $this->table . " 
                    SET traite = 0 
                    WHERE id_contact = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Supprimer un message
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " 
                    WHERE id_contact = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Compter les messages non traités
    public function countUntreated() {
        $query = "SELECT COUNT(*) as total 
                    FROM " . $this->table . " 
                    WHERE traite = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}