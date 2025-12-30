<?php

class FavoriController {
    
    // Toggle favori (AJAX)
    public function toggle() {
        header('Content-Type: application/json');
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Vous devez être connecté']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }
        
        $menu_id = $_POST['menu_id'] ?? null;
        
        if (!$menu_id) {
            echo json_encode(['success' => false, 'message' => 'Menu invalide']);
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $favoriModel = new Favori($conn);
        
        $user_id = $_SESSION['user_id'];
        $isFavorite = $favoriModel->isFavorite($user_id, $menu_id);
        
        if ($isFavorite) {
            // Supprimer le favori
            if ($favoriModel->remove($user_id, $menu_id)) {
                echo json_encode(['success' => true, 'action' => 'removed', 'message' => 'Retiré des favoris']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
            }
        } else {
            // Ajouter le favori
            if ($favoriModel->add($user_id, $menu_id)) {
                echo json_encode(['success' => true, 'action' => 'added', 'message' => 'Ajouté aux favoris']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
            }
        }
        exit;
    }
    
    // Page mes favoris
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez être connecté';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $favoriModel = new Favori($conn);
        
        $favoris = $favoriModel->getByUser($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/user/favoris.php';
    }
}