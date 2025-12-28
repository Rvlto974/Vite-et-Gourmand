<?php

class MenuController {
    
    // Liste de tous les menus
    public function index() {
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        // Récupérer les filtres
        $theme_id = isset($_GET['theme']) ? $_GET['theme'] : null;
        $regime_id = isset($_GET['regime']) ? $_GET['regime'] : null;
        
        // Appliquer les filtres
        if ($theme_id) {
            $menus = $menuModel->getByTheme($theme_id);
        } elseif ($regime_id) {
            $menus = $menuModel->getByRegime($regime_id);
        } else {
            $menus = $menuModel->getAll();
        }
        
        // Récupérer les thèmes et régimes pour les filtres
        $themes = $menuModel->getAllThemes();
        $regimes = $menuModel->getAllRegimes();
        
        // Charger la vue
        require_once __DIR__ . '/../views/menu/index.php';
    }
    
    // Détail d'un menu
    public function show($id) {
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        // Récupérer le menu
        $menu = $menuModel->getById($id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu');
            exit;
        }
        
        // Récupérer les plats et images
        $plats = $menuModel->getPlats($id);
        $images = $menuModel->getImages($id);
        
        // Organiser les plats par type
        $entrees = array_filter($plats, fn($p) => $p['type_plat'] === 'entree');
        $plats_principaux = array_filter($plats, fn($p) => $p['type_plat'] === 'plat');
        $desserts = array_filter($plats, fn($p) => $p['type_plat'] === 'dessert');
        
        // Charger la vue
        require_once __DIR__ . '/../views/menu/show.php';
    }
    
    // Liste des menus pour l'admin
    public function adminList() {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        // Récupérer tous les menus (actifs et inactifs)
        $query = "SELECT m.*, t.nom_theme, r.nom_regime 
                  FROM menu m
                  LEFT JOIN theme t ON m.id_theme = t.id_theme
                  LEFT JOIN regime r ON m.id_regime = r.id_regime
                  ORDER BY m.date_creation DESC";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $menus = $stmt->fetchAll();
        
        $themes = $menuModel->getAllThemes();
        $regimes = $menuModel->getAllRegimes();
        
        require_once __DIR__ . '/../views/admin/menus.php';
    }
    
    // Formulaire d'ajout de menu
    public function adminCreate() {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        $themes = $menuModel->getAllThemes();
        $regimes = $menuModel->getAllRegimes();
        
        require_once __DIR__ . '/../views/admin/menu-create.php';
    }
    
    // Enregistrer un nouveau menu
    public function adminStore() {
        AdminMiddleware::check();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /menu/adminList');
            exit;
        }
        
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $prix_base = $_POST['prix_base'] ?? 0;
        $nb_personnes_min = $_POST['nb_personnes_min'] ?? 1;
        $stock_disponible = $_POST['stock_disponible'] ?? 0;
        $id_theme = $_POST['id_theme'] ?? null;
        $id_regime = $_POST['id_regime'] ?? null;
        $conditions = trim($_POST['conditions'] ?? '');
        $actif = isset($_POST['actif']) ? 1 : 0;
        
        // Validation
        if (empty($titre) || empty($description) || $prix_base <= 0 || $nb_personnes_min < 1) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent etre remplis';
            header('Location: /menu/adminCreate');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "INSERT INTO menu (titre, description, prix_base, nb_personnes_min, stock_disponible, 
                                     id_theme, id_regime, conditions, actif)
                  VALUES (:titre, :description, :prix_base, :nb_personnes_min, :stock_disponible,
                          :id_theme, :id_regime, :conditions, :actif)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix_base', $prix_base);
        $stmt->bindParam(':nb_personnes_min', $nb_personnes_min);
        $stmt->bindParam(':stock_disponible', $stock_disponible);
        $stmt->bindParam(':id_theme', $id_theme);
        $stmt->bindParam(':id_regime', $id_regime);
        $stmt->bindParam(':conditions', $conditions);
        $stmt->bindParam(':actif', $actif);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Menu cree avec succes';
            header('Location: /menu/adminList');
        } else {
            $_SESSION['error'] = 'Erreur lors de la creation du menu';
            header('Location: /menu/adminCreate');
        }
        exit;
    }
    
    // Formulaire d'édition de menu
    public function adminEdit($menu_id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        $menu = $menuModel->getById($menu_id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu/adminList');
            exit;
        }
        
        $themes = $menuModel->getAllThemes();
        $regimes = $menuModel->getAllRegimes();
        
        require_once __DIR__ . '/../views/admin/menu-edit.php';
    }
    
    // Mettre à jour un menu
    public function adminUpdate() {
        AdminMiddleware::check();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /menu/adminList');
            exit;
        }
        
        $id_menu = $_POST['id_menu'] ?? null;
        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $prix_base = $_POST['prix_base'] ?? 0;
        $nb_personnes_min = $_POST['nb_personnes_min'] ?? 1;
        $stock_disponible = $_POST['stock_disponible'] ?? 0;
        $id_theme = $_POST['id_theme'] ?? null;
        $id_regime = $_POST['id_regime'] ?? null;
        $conditions = trim($_POST['conditions'] ?? '');
        $actif = isset($_POST['actif']) ? 1 : 0;
        
        if (!$id_menu) {
            $_SESSION['error'] = 'Menu invalide';
            header('Location: /menu/adminList');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        $query = "UPDATE menu 
                  SET titre = :titre, description = :description, prix_base = :prix_base,
                      nb_personnes_min = :nb_personnes_min, stock_disponible = :stock_disponible,
                      id_theme = :id_theme, id_regime = :id_regime, conditions = :conditions, actif = :actif
                  WHERE id_menu = :id_menu";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':prix_base', $prix_base);
        $stmt->bindParam(':nb_personnes_min', $nb_personnes_min);
        $stmt->bindParam(':stock_disponible', $stock_disponible);
        $stmt->bindParam(':id_theme', $id_theme);
        $stmt->bindParam(':id_regime', $id_regime);
        $stmt->bindParam(':conditions', $conditions);
        $stmt->bindParam(':actif', $actif);
        $stmt->bindParam(':id_menu', $id_menu);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Menu mis a jour avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise a jour';
        }
        
        header('Location: /menu/adminEdit/' . $id_menu);
        exit;
    }
    
    // Supprimer un menu
    public function adminDelete($menu_id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        
        // Vérifier si le menu a des commandes
        $query = "SELECT COUNT(*) as nb FROM commande WHERE id_menu = :id_menu";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_menu', $menu_id);
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result['nb'] > 0) {
            $_SESSION['error'] = 'Impossible de supprimer ce menu car il a des commandes associees';
            header('Location: /menu/adminList');
            exit;
        }
        
        // Supprimer le menu
        $query = "DELETE FROM menu WHERE id_menu = :id_menu";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_menu', $menu_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Menu supprime avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        
        header('Location: /menu/adminList');
        exit;
    }
}