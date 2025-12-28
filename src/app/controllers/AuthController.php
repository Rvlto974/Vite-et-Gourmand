<?php

class AuthController {
    
    // Afficher le formulaire de connexion
    public function login() {
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    // Traiter la connexion
    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Tous les champs sont obligatoires';
                header('Location: /auth/login');
                exit;
            }
            
            // Vérifier les identifiants
            $db = new Database();
            $conn = $db->getConnection();
            $user = new User($conn);
            $user->email = $email;
            
            if ($user->login($password)) {
                // Connexion réussie
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_nom'] = $user->nom;
                $_SESSION['user_prenom'] = $user->prenom;
                $_SESSION['user_email'] = $user->email;
                $_SESSION['user_role'] = $user->role;
                $_SESSION['success'] = 'Connexion reussie ! Bienvenue ' . $user->prenom;
                
                header('Location: /');
                exit;
            } else {
                $_SESSION['error'] = 'Email ou mot de passe incorrect';
                header('Location: /auth/login');
                exit;
            }
        }
    }
    
    // Afficher le formulaire d'inscription
    public function register() {
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    // Traiter l'inscription
    public function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $gsm = trim($_POST['gsm'] ?? '');
            $adresse = trim($_POST['adresse'] ?? '');
            
            // Validation
            if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($gsm) || empty($adresse)) {
                $_SESSION['error'] = 'Tous les champs sont obligatoires';
                header('Location: /auth/register');
                exit;
            }
            
            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
                header('Location: /auth/register');
                exit;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caracteres';
                header('Location: /auth/register');
                exit;
            }
            
            // Créer l'utilisateur
            $db = new Database();
            $conn = $db->getConnection();
            $user = new User($conn);
            
            $user->email = $email;
            
            // Vérifier si email existe déjà
            if ($user->emailExists()) {
                $_SESSION['error'] = 'Cet email est deja utilise';
                header('Location: /auth/register');
                exit;
            }
            
            // Enregistrer
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->mot_de_passe = $password;
            $user->gsm = $gsm;
            $user->adresse_postale = $adresse;
            
            if ($user->register()) {
                $_SESSION['success'] = 'Inscription reussie ! Vous pouvez vous connecter';
                header('Location: /auth/login');
                exit;
            } else {
                $_SESSION['error'] = 'Erreur lors de l inscription';
                header('Location: /auth/register');
                exit;
            }
        }
    }
    
    // Déconnexion
    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}