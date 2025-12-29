<?php

class HomeController {
    
    public function index() {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Top 3 menus les plus commandés
        $query = "SELECT m.id_menu, m.titre, m.description, m.prix_base, t.nom_theme, r.nom_regime, 
                  (SELECT COUNT(*) FROM commande c WHERE c.id_menu = m.id_menu) as nb_commandes
                  FROM menu m
                  LEFT JOIN theme t ON m.id_theme = t.id_theme
                  LEFT JOIN regime r ON m.id_regime = r.id_regime
                  WHERE m.actif = 1
                  ORDER BY nb_commandes DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $all_menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // DÉDOUBLONNAGE MANUEL EN PHP
        $seen_ids = [];
        $top_menus = [];
        foreach ($all_menus as $menu) {
            if (!in_array($menu['id_menu'], $seen_ids)) {
                $seen_ids[] = $menu['id_menu'];
                $top_menus[] = $menu;
                if (count($top_menus) >= 3) break;
            }
        }
        
        // Récupérer les images des menus
        $menuModel = new Menu($conn);
        foreach ($top_menus as &$menu) {
            $images = $menuModel->getImages($menu['id_menu']);
            $menu['image'] = !empty($images) ? $images[0]['chemin_fichier'] : '/uploads/menus/default-menu.jpg';
        }
        
        // Derniers avis validés
        $query = "SELECT a.*, u.prenom, m.titre as menu_titre
                  FROM avis a
                  INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                  INNER JOIN commande c ON a.id_commande = c.id_commande
                  INNER JOIN menu m ON c.id_menu = m.id_menu
                  WHERE a.valide = 1
                  ORDER BY a.date_creation DESC
                  LIMIT 3";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $temoignages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once __DIR__ . '/../views/home/index.php';
    }
    
    public function about() {
        require_once __DIR__ . '/../views/home/about.php';
    }
}