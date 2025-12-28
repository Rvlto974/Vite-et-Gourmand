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
}