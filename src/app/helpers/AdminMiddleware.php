<?php

class AdminMiddleware {
    
    public static function check() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte pour acceder a cette page';
            header('Location: /auth/login');
            exit;
        }
        
        // Vérifier si l'utilisateur est admin
        if ($_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = 'Acces refuse : vous n\'avez pas les droits administrateur';
            header('Location: /');
            exit;
        }
        
        return true;
    }
}