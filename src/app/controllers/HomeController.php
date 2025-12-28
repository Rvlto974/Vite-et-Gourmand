<?php

class HomeController {
    
    public function index() {
        // Tester la connexion BDD
        $db = new Database();
        $conn = $db->getConnection();
        
        // Récupérer les menus depuis la BDD
        $query = "SELECT * FROM menu WHERE actif = 1 LIMIT 3";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $menus = $stmt->fetchAll();
        
        // Charger la vue
        require_once __DIR__ . '/../views/home/index.php';
    }
    
    public function about() {
        echo "<h1>À propos de Vite & Gourmand</h1>";
        echo "<p>Traiteur événementiel à Bordeaux depuis 25 ans !</p>";
    }
}