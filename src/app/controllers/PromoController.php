<?php

class PromoController {
    
    // Liste des codes promo (admin)
    public function adminList() {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $promoModel = new Promo($conn);
        
        $promos = $promoModel->getAll();
        
        require_once __DIR__ . '/../views/admin/promos.php';
    }
    
    // Formulaire de création
    public function adminCreate() {
        AdminMiddleware::check();
        require_once __DIR__ . '/../views/admin/promo-create.php';
    }
    
    // Enregistrer un nouveau code
    public function adminStore() {
        AdminMiddleware::check();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /promo/adminList');
            exit;
        }
        
        $data = [
            'code' => strtoupper(trim($_POST['code'] ?? '')),
            'type' => $_POST['type'] ?? '',
            'valeur' => $_POST['valeur'] ?? 0,
            'montant_minimum' => $_POST['montant_minimum'] ?? 0,
            'date_debut' => $_POST['date_debut'] ?? '',
            'date_fin' => $_POST['date_fin'] ?? '',
            'utilisation_max' => !empty($_POST['utilisation_max']) ? $_POST['utilisation_max'] : null,
            'actif' => isset($_POST['actif']) ? 1 : 0
        ];
        
        // Validation
        if (empty($data['code']) || empty($data['type']) || empty($data['valeur'])) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            header('Location: /promo/adminCreate');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $promoModel = new Promo($conn);
        
        if ($promoModel->create($data)) {
            $_SESSION['success'] = 'Code promo créé avec succès';
        } else {
            $_SESSION['error'] = 'Erreur lors de la création';
        }
        
        header('Location: /promo/adminList');
        exit;
    }
    
    // Supprimer un code
    public function adminDelete($id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $promoModel = new Promo($conn);
        
        if ($promoModel->delete($id)) {
            $_SESSION['success'] = 'Code promo supprimé';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        header('Location: /promo/adminList');
        exit;
    }
}