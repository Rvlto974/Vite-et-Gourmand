<?php

class ReservationController {
    
    // Page calendrier des réservations (public)
    public function calendar() {
        require_once __DIR__ . '/../views/reservation/calendar.php';
    }
    
    // Formulaire de réservation
    public function create($menu_id) {
        $db = new Database();
        $conn = $db->getConnection();
        
        // Récupérer le menu
        $menuModel = new Menu($conn);
        $menu = $menuModel->getById($menu_id);
        
        if (!$menu) {
            $_SESSION['error'] = 'Menu introuvable';
            header('Location: /menu');
            exit;
        }
        
        require_once __DIR__ . '/../views/reservation/create.php';
    }
    
    // Traiter la création de réservation
    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez être connecté';
            header('Location: /auth/login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /menu');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        
        // Récupérer les données
        $menu_id = $_POST['menu_id'] ?? null;
        $date_reservation = $_POST['date_reservation'] ?? null;
        $heure_debut = $_POST['heure_debut'] ?? null;
        $nb_personnes = $_POST['nb_personnes'] ?? null;
        $adresse = $_POST['adresse_livraison'] ?? null;
        $commentaire = $_POST['commentaire'] ?? '';
        
        // Validation
        if (!$menu_id || !$date_reservation || !$heure_debut || !$nb_personnes || !$adresse) {
            $_SESSION['error'] = 'Tous les champs obligatoires doivent être remplis';
            header('Location: /reservation/create/' . $menu_id);
            exit;
        }
        
        // Calculer heure_fin (2h après le début)
        $heure_fin = date('H:i:s', strtotime($heure_debut) + 7200);
        
        // Vérifier disponibilité
        $reservationModel = new Reservation($conn);
        if (!$reservationModel->checkAvailability($date_reservation, $heure_debut, $heure_fin)) {
            $_SESSION['error'] = 'Ce créneau n\'est plus disponible';
            header('Location: /reservation/create/' . $menu_id);
            exit;
        }
        
        // Récupérer le menu
        $menuModel = new Menu($conn);
        $menu = $menuModel->getById($menu_id);
        
        // Calculer le prix
        $prix_total = $menu['prix_base'] * $nb_personnes;
        
        // Créer la réservation
        $reservation_id = $reservationModel->create([
            'id_utilisateur' => $_SESSION['user_id'],
            'id_menu' => $menu_id,
            'date_reservation' => $date_reservation,
            'heure_debut' => $heure_debut,
            'heure_fin' => $heure_fin,
            'nb_personnes' => $nb_personnes,
            'adresse_livraison' => $adresse,
            'commentaire' => $commentaire,
            'prix_total' => $prix_total,
            'statut' => 'confirmee'
        ]);
        
        if ($reservation_id) {
            // Envoyer email de confirmation
            require_once __DIR__ . '/../helpers/Email.php';
            require_once __DIR__ . '/../helpers/EmailTemplates.php';
            
            $email = new Email();
            $content = EmailTemplates::confirmationReservation([
                'id_reservation' => $reservation_id,
                'menu_titre' => $menu['titre'],
                'date_reservation' => date('d/m/Y', strtotime($date_reservation)),
                'heure_debut' => date('H:i', strtotime($heure_debut)),
                'nb_personnes' => $nb_personnes,
                'prix_total' => $prix_total
            ]);
            
            $email->send(
                $_SESSION['user_email'],
                'Confirmation de réservation - Vite & Gourmand',
                $email->getTemplate($content)
            );
            
            $_SESSION['success'] = 'Réservation confirmée ! Un email de confirmation vous a été envoyé.';
            header('Location: /reservation/confirmation/' . $reservation_id);
            exit;
        } else {
            $_SESSION['error'] = 'Erreur lors de la réservation';
            header('Location: /reservation/create/' . $menu_id);
            exit;
        }
    }
    
    // Page de confirmation
    public function confirmation($reservation_id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $reservationModel = new Reservation($conn);
        
        $reservation = $reservationModel->getById($reservation_id);
        
        if (!$reservation || $reservation['id_utilisateur'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Réservation introuvable';
            header('Location: /user/dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/reservation/confirmation.php';
    }
    
    // Mes réservations
    public function myReservations() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $reservationModel = new Reservation($conn);
        
        $reservations = $reservationModel->getByUser($_SESSION['user_id']);
        
        require_once __DIR__ . '/../views/reservation/my-reservations.php';
    }
    
    // API : Obtenir les créneaux disponibles (JSON)
    public function availableSlots() {
        header('Content-Type: application/json');
        
        $date = $_GET['date'] ?? null;
        
        if (!$date) {
            echo json_encode(['slots' => []]);
            exit;
        }
        
        // Créneaux possibles de 9h à 21h
        $slots = [];
        for ($h = 9; $h <= 19; $h++) {
            $heure_debut = sprintf('%02d:00:00', $h);
            $heure_fin = sprintf('%02d:00:00', $h + 2);
            
            $db = new Database();
            $conn = $db->getConnection();
            $reservationModel = new Reservation($conn);
            
            if ($reservationModel->checkAvailability($date, $heure_debut, $heure_fin)) {
                $slots[] = [
                    'time' => sprintf('%02d:00', $h),
                    'label' => sprintf('%02d:00 - %02d:00', $h, $h + 2)
                ];
            }
        }
        
        echo json_encode(['slots' => $slots]);
        exit;
    }
}