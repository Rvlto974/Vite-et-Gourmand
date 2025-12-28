<?php

class UserController {
    
    // Dashboard utilisateur
    public function dashboard() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte pour acceder a votre espace';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        // Récupérer les infos de l'utilisateur
        $userQuery = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $conn->prepare($userQuery);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Récupérer les statistiques
        $statsQuery = "SELECT 
                        COUNT(*) as total_commandes,
                        SUM(prix_total) as total_depense,
                        MAX(date_creation) as derniere_commande
                       FROM commande 
                       WHERE id_utilisateur = :id";
        $stmt = $conn->prepare($statsQuery);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $stats = $stmt->fetch();
        
        // Récupérer les dernières commandes
        $orderModel = new Order($conn);
        $orders = $orderModel->getByUser($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/user/dashboard.php';
    }
    
    // Historique des commandes
    public function orders() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $orderModel = new Order($conn);
        $orders = $orderModel->getByUser($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/user/orders.php';
    }
    
    // Détail d'une commande
    public function orderDetail($order_id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $orderModel = new Order($conn);
        $order = $orderModel->getById($order_id);
        
        // Vérifier que la commande appartient à l'utilisateur
        if (!$order || $order['id_utilisateur'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Commande introuvable';
            header('Location: /user/orders');
            exit;
        }
        
        require_once __DIR__ . '/../views/user/order-detail.php';
    }
    
    // Profil utilisateur
    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();
        
        require_once __DIR__ . '/../views/user/profile.php';
    }
    
    // Mettre à jour le profil
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /user/profile');
            exit;
        }
        
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $gsm = trim($_POST['gsm'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        
        if (empty($nom) || empty($prenom) || empty($gsm) || empty($adresse)) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            header('Location: /user/profile');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "UPDATE utilisateur 
                    SET nom = :nom, prenom = :prenom, gsm = :gsm, adresse_postale = :adresse
                    WHERE id_utilisateur = :id";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':gsm', $gsm);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Mettre à jour la session
            $_SESSION['user_nom'] = $nom;
            $_SESSION['user_prenom'] = $prenom;
            
            $_SESSION['success'] = 'Profil mis a jour avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise a jour';
        }
        
        header('Location: /user/profile');
        exit;
    }
}