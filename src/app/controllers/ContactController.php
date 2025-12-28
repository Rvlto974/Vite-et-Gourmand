<?php

class ContactController {
    
    // Afficher le formulaire de contact
    public function index() {
        require_once __DIR__ . '/../views/contact/index.php';
    }
    
    // Enregistrer un message de contact
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contact');
            exit;
        }
        
        $email = trim($_POST['email'] ?? '');
        $titre = trim($_POST['titre'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validation
        if (empty($email) || empty($titre) || empty($message)) {
            $_SESSION['error'] = 'Tous les champs sont obligatoires';
            header('Location: /contact');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Email invalide';
            header('Location: /contact');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $contactModel = new Contact($conn);
        
        $data = [
            'email' => $email,
            'titre' => $titre,
            'message' => $message
        ];
        
        if ($contactModel->create($data)) {
            $_SESSION['success'] = 'Votre message a ete envoye avec succes ! Nous vous repondrons dans les plus brefs delais.';
            header('Location: /contact');
        } else {
            $_SESSION['error'] = 'Erreur lors de l envoi du message';
            header('Location: /contact');
        }
        exit;
    }
    
    // Liste des messages (admin)
    public function adminList() {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $contactModel = new Contact($conn);
        
        $messages = $contactModel->getAll();
        
        require_once __DIR__ . '/../views/admin/contact.php';
    }
    
    // Marquer comme traité/non traité
    public function adminToggleStatus($id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $contactModel = new Contact($conn);
        
        $message = $contactModel->getById($id);
        
        if (!$message) {
            $_SESSION['error'] = 'Message introuvable';
            header('Location: /contact/adminList');
            exit;
        }
        
        if ($message['traite']) {
            $contactModel->markAsUntreated($id);
            $_SESSION['success'] = 'Message marque comme non traite';
        } else {
            $contactModel->markAsTreated($id);
            $_SESSION['success'] = 'Message marque comme traite';
        }
        
        header('Location: /contact/adminList');
        exit;
    }
    
    // Supprimer un message
    public function adminDelete($id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $contactModel = new Contact($conn);
        
        if ($contactModel->delete($id)) {
            $_SESSION['success'] = 'Message supprime avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        header('Location: /contact/adminList');
        exit;
    }
}