<?php

class OrderController {
    
    // Afficher le formulaire de commande
    public function create($menu_id) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte pour commander';
            header('Location: /auth/login');
            exit;
        }
        
        // Récupérer le menu
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        $menu = $menuModel->getById($menu_id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu');
            exit;
        }
        
        // Vérifier le stock
        if ($menu['stock_disponible'] <= 0) {
            $_SESSION['error'] = 'Ce menu n est plus disponible';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        // Récupérer l'adresse de l'utilisateur
        $userQuery = "SELECT adresse_postale FROM utilisateur WHERE id_utilisateur = :id";
        $stmt = $conn->prepare($userQuery);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch();
        
        // Charger la vue
        require_once __DIR__ . '/../views/order/create.php';
    }
    
    // Traiter la commande
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /menu');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez etre connecte pour commander';
            header('Location: /auth/login');
            exit;
        }
        
        // Récupérer les données du formulaire
        $menu_id = $_POST['menu_id'] ?? null;
        $nb_personnes = $_POST['nb_personnes'] ?? null;
        $adresse_livraison = trim($_POST['adresse_livraison'] ?? '');
        $date_livraison = $_POST['date_livraison'] ?? null;
        $heure_livraison = $_POST['heure_livraison'] ?? null;
        
        // Validation
        $errors = [];
        
        if (empty($menu_id) || !is_numeric($menu_id)) {
            $errors[] = 'Menu invalide';
        }
        
        if (empty($nb_personnes) || !is_numeric($nb_personnes) || $nb_personnes < 1) {
            $errors[] = 'Nombre de personnes invalide';
        }
        
        if (empty($adresse_livraison)) {
            $errors[] = 'Adresse de livraison obligatoire';
        }
        
        if (empty($date_livraison)) {
            $errors[] = 'Date de livraison obligatoire';
        } else {
            // Vérifier que la date est dans le futur et au moins 7 jours à l'avance
            $date_obj = new DateTime($date_livraison);
            $today = new DateTime();
            $min_date = (new DateTime())->modify('+7 days');
            
            if ($date_obj < $min_date) {
                $errors[] = 'La commande doit etre passee au moins 7 jours avant la livraison';
            }
        }
        
        if (empty($heure_livraison)) {
            $errors[] = 'Heure de livraison obligatoire';
        }
        
        // Si erreurs, retour au formulaire
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: /order/create/' . $menu_id);
            exit;
        }
        
        // Récupérer le menu
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        $menu = $menuModel->getById($menu_id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu');
            exit;
        }
        
        // Vérifier le nombre minimum de personnes
        if ($nb_personnes < $menu['nb_personnes_min']) {
            $_SESSION['error'] = 'Minimum ' . $menu['nb_personnes_min'] . ' personnes pour ce menu';
            header('Location: /order/create/' . $menu_id);
            exit;
        }
        
        // Vérifier le stock
        if ($menu['stock_disponible'] <= 0) {
            $_SESSION['error'] = 'Ce menu n est plus disponible';
            header('Location: /menu/show/' . $menu_id);
            exit;
        }
        
        // Calculer le prix
        $orderModel = new Order($conn);
        $prix = $orderModel->calculateTotal(
            $menu['prix_base'], 
            $nb_personnes, 
            $menu['nb_personnes_min']
        );
        
        // Préparer les données
        $orderData = [
            'id_utilisateur' => $_SESSION['user_id'],
            'id_menu' => $menu_id,
            'nb_personnes' => $nb_personnes,
            'prix_menu' => $prix['prix_menu'],
            'prix_livraison' => $prix['prix_livraison'],
            'prix_total' => $prix['prix_total'],
            'adresse_livraison' => $adresse_livraison,
            'date_livraison' => $date_livraison,
            'heure_livraison' => $heure_livraison
        ];
        
        // Créer la commande
        $order_id = $orderModel->create($orderData);
        
        if ($order_id) {
            $_SESSION['success'] = 'Commande enregistree avec succes ! Numero de commande : ' . $order_id;
            header('Location: /order/confirmation/' . $order_id);
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de l enregistrement de la commande';
            header('Location: /order/create/' . $menu_id);
            exit;
        }
    }
    
    // Page de confirmation
    public function confirmation($order_id) {
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
            header('Location: /menu');
            exit;
        }
        
        require_once __DIR__ . '/../views/order/confirmation.php';
    }
}