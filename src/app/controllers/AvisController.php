<?php

class AvisController {
    
    // Formulaire pour laisser un avis
    public function create($menu_id) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte pour laisser un avis';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        $avisModel = new Avis($conn);
        
        // Récupérer le menu
        $menu = $menuModel->getById($menu_id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu');
            exit;
        }
        
        // Vérifier si l'utilisateur a déjà laissé un avis
        if ($avisModel->userHasReviewed($_SESSION['user_id'], $menu_id)) {
            $_SESSION['error'] = 'Vous avez deja laisse un avis pour ce menu';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        // Vérifier si l'utilisateur a commandé et reçu ce menu
        if (!$avisModel->userCanReview($_SESSION['user_id'], $menu_id)) {
            $_SESSION['error'] = 'Vous devez avoir commande et recu ce menu pour laisser un avis';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        require_once __DIR__ . '/../views/avis/create.php';
    }
    
    // Enregistrer un avis
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: /menu');
            exit;
        }
        
        $menu_id = $_POST['menu_id'] ?? null;
        $note = $_POST['note'] ?? null;
        $commentaire = trim($_POST['commentaire'] ?? '');
        
        // Validation
        if (!$menu_id || !$note || $note < 1 || $note > 5) {
            $_SESSION['error'] = 'Donnees invalides';
            header('Location: /avis/create/' . $menu_id);
            exit;
        }
        
        if (empty($commentaire)) {
            $_SESSION['error'] = 'Le commentaire est obligatoire';
            header('Location: /avis/create/' . $menu_id);
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $avisModel = new Avis($conn);
        
        // Vérifier à nouveau les conditions
        if ($avisModel->userHasReviewed($_SESSION['user_id'], $menu_id)) {
            $_SESSION['error'] = 'Vous avez deja laisse un avis pour ce menu';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        if (!$avisModel->userCanReview($_SESSION['user_id'], $menu_id)) {
            $_SESSION['error'] = 'Vous ne pouvez pas laisser d avis pour ce menu';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        // Récupérer l'id de la commande livrée
        $id_commande = $avisModel->getDeliveredOrder($_SESSION['user_id'], $menu_id);

        if (!$id_commande) {
            $_SESSION['error'] = 'Aucune commande livree trouvee pour ce menu';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }

        // Créer l'avis
        $data = [
            'id_utilisateur' => $_SESSION['user_id'],
            'id_commande' => $id_commande,
            'note' => $note,
            'commentaire' => $commentaire
        ];
        
        if ($avisModel->create($data)) {
            $_SESSION['success'] = 'Votre avis a ete envoye et sera valide par un administrateur';
            header('Location: /menu/show/' . $menu_id);
        } else {
            $_SESSION['error'] = 'Erreur lors de l enregistrement de l avis';
            header('Location: /avis/create/' . $menu_id);
        }
        exit;
    }
    
    // Liste des avis (admin)
    public function adminList() {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $avisModel = new Avis($conn);
        
        $avis = $avisModel->getAll();
        
        require_once __DIR__ . '/../views/admin/avis.php';
    }
    
    // Changer le statut d'un avis (admin)
    public function adminUpdateStatus() {
        AdminMiddleware::check();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /avis/adminList');
            exit;
        }
        
        $avis_id = $_POST['avis_id'] ?? null;
        $statut = $_POST['statut'] ?? null;
        
        if (!$avis_id || !$statut) {
            $_SESSION['error'] = 'Donnees invalides';
            header('Location: /avis/adminList');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $avisModel = new Avis($conn);
        
        if ($avisModel->updateStatus($avis_id, $statut)) {
            $_SESSION['success'] = 'Statut de l avis mis a jour';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise a jour';
        }
        
        header('Location: /avis/adminList');
        exit;
    }
    
    // Supprimer un avis (admin)
    public function adminDelete($avis_id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $avisModel = new Avis($conn);
        
        if ($avisModel->delete($avis_id)) {
            $_SESSION['success'] = 'Avis supprime avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        header('Location: /avis/adminList');
        exit;
    }
}