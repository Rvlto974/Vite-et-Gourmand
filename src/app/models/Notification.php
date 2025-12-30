<?php

class Notification {
    private $conn;
    private $table = 'notifications';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Créer une notification
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (id_utilisateur, type, titre, message, id_reference, url) 
                  VALUES (:user_id, :type, :titre, :message, :reference, :url)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $data['id_utilisateur']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':titre', $data['titre']);
        $stmt->bindParam(':message', $data['message']);
        $stmt->bindParam(':reference', $data['id_reference']);
        $stmt->bindParam(':url', $data['url']);
        
        return $stmt->execute();
    }
    
    // Récupérer les notifications d'un utilisateur
    public function getByUser($user_id, $limit = 20) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_utilisateur = :user_id 
                  ORDER BY date_creation DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Compter les notifications non lues
    public function countUnread($user_id) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE id_utilisateur = :user_id AND lue = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result['count'];
    }
    
    // Marquer comme lue
    public function markAsRead($notification_id) {
        $query = "UPDATE " . $this->table . " SET lue = 1 WHERE id_notification = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notification_id);
        
        return $stmt->execute();
    }
    
    // Marquer toutes comme lues
    public function markAllAsRead($user_id) {
        $query = "UPDATE " . $this->table . " SET lue = 1 WHERE id_utilisateur = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    
    // Récupérer les nouvelles notifications (pour SSE)
    public function getNewSince($user_id, $since_id = 0) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_utilisateur = :user_id 
                  AND id_notification > :since_id 
                  ORDER BY id_notification ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':since_id', $since_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Supprimer une notification
    public function delete($notification_id) {
        $query = "DELETE FROM " . $this->table . " WHERE id_notification = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $notification_id);
        
        return $stmt->execute();
    }
}