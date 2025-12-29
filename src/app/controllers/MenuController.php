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
            $menu_id = $conn->lastInsertId();
            
            // Gérer l'upload d'image
            error_log("=== DEBUT GESTION UPLOAD (CREATE) ===");
            error_log("isset FILES image: " . (isset($_FILES['image']) ? 'OUI' : 'NON'));
            
            if (isset($_FILES['image'])) {
                error_log("FILES image error: " . $_FILES['image']['error']);
                error_log("UPLOAD_ERR_OK = " . UPLOAD_ERR_OK);
                
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    error_log("=== IMAGE DETECTEE ===");
                    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                    $file_type = $_FILES['image']['type'];
                    error_log("Type fichier: " . $file_type);
                    
                    if (in_array($file_type, $allowed_types)) {
                        error_log("=== TYPE VALIDE ===");
                        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = 'menu_' . $menu_id . '_' . time() . '.' . $extension;
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/menus/' . $filename;
                        error_log("Upload path: " . $upload_path);
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                            error_log("=== FICHIER UPLOADE ===");
                            $menuModel = new Menu($conn);
                            error_log("=== APPEL addImage ===");
                            $result = $menuModel->addImage($menu_id, '/uploads/menus/' . $filename);
                            error_log("=== RESULTAT addImage: " . ($result ? 'OK' : 'ERREUR') . " ===");
                        } else {
                            error_log("ERREUR move_uploaded_file");
                        }
                    } else {
                        error_log("Type fichier non valide: " . $file_type);
                    }
                } else {
                    error_log("Erreur upload, code: " . $_FILES['image']['error']);
                }
            }
            
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
        $images = $menuModel->getImages($menu_id);
        
        require_once __DIR__ . '/../views/admin/menu-edit.php';
    }
    
    // Mettre à jour un menu
    public function adminUpdate() {
        AdminMiddleware::check();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /menu/adminList');
            exit;
        }
        
        // DEBUG
        error_log("=== DEBUG UPLOAD ===");
        error_log("FILES: " . print_r($_FILES, true));
        
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
            // Gérer l'upload d'image
            error_log("=== DEBUT GESTION UPLOAD (UPDATE) ===");
            error_log("isset FILES image: " . (isset($_FILES['image']) ? 'OUI' : 'NON'));
            
            if (isset($_FILES['image'])) {
                error_log("FILES image error: " . $_FILES['image']['error']);
                error_log("UPLOAD_ERR_OK = " . UPLOAD_ERR_OK);
                
                if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    error_log("=== IMAGE DETECTEE ===");
                    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                    $file_type = $_FILES['image']['type'];
                    error_log("Type fichier: " . $file_type);
                    
                    if (in_array($file_type, $allowed_types)) {
                        error_log("=== TYPE VALIDE ===");
                        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                        $filename = 'menu_' . $id_menu . '_' . time() . '.' . $extension;
                        $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/menus/' . $filename;
                        error_log("Upload path: " . $upload_path);
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                            error_log("=== FICHIER UPLOADE ===");
                            $menuModel = new Menu($conn);
                            error_log("=== APPEL addImage ===");
                            $result = $menuModel->addImage($id_menu, '/uploads/menus/' . $filename);
                            error_log("=== RESULTAT addImage: " . ($result ? 'OK' : 'ERREUR') . " ===");
                        } else {
                            error_log("ERREUR move_uploaded_file");
                        }
                    } else {
                        error_log("Type fichier non valide: " . $file_type);
                    }
                } else {
                    error_log("Erreur upload, code: " . $_FILES['image']['error']);
                }
            }
            
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
    
    // Supprimer une image
    public function adminDeleteImage($image_id) {
        AdminMiddleware::check();
        
        $db = new Database();
        $conn = $db->getConnection();
        $menuModel = new Menu($conn);
        
        // Récupérer l'id du menu avant de supprimer
        $query = "SELECT id_menu FROM image_menu WHERE id_image = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $image_id);
        $stmt->execute();
        $result = $stmt->fetch();
        $menu_id = $result['id_menu'] ?? null;
        
        if ($menuModel->deleteImage($image_id)) {
            $_SESSION['success'] = 'Image supprimee avec succes';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression de l image';
        }
        
        if ($menu_id) {
            header('Location: /menu/adminEdit/' . $menu_id);
        } else {
            header('Location: /menu/adminList');
        }
        exit;
    }
}