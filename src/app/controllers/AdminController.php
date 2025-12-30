<?php

class AdminController {

    public function __construct() {
        // Vérifier les droits admin
        AdminMiddleware::check();
    }

    // Dashboard admin
    public function index() {
        $db = new Database();
        $conn = $db->getConnection();

        // Statistiques générales
        $stats = [];

        // Total commandes
        $query = "SELECT COUNT(*) as total FROM commande";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stats['total_commandes'] = $stmt->fetch()['total'];

        // Total utilisateurs
        $query = "SELECT COUNT(*) as total FROM utilisateur";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stats['total_utilisateurs'] = $stmt->fetch()['total'];

        // Total menus
        $query = "SELECT COUNT(*) as total FROM menu WHERE actif = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stats['total_menus'] = $stmt->fetch()['total'];

        // Chiffre d'affaires total
        $query = "SELECT SUM(prix_total) as ca FROM commande WHERE statut != 'annulee'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stats['chiffre_affaires'] = $stmt->fetch()['ca'] ?? 0;

        // Commandes par statut
        $query = "SELECT statut, COUNT(*) as nombre FROM commande GROUP BY statut";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $stats['commandes_par_statut'] = $stmt->fetchAll();

        // Dernières commandes
        $query = "SELECT c.*, m.titre as menu_titre, u.nom, u.prenom, u.email
                    FROM commande c
                    INNER JOIN menu m ON c.id_menu = m.id_menu
                    INNER JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
                    ORDER BY c.date_creation DESC
                    LIMIT 10";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $dernieres_commandes = $stmt->fetchAll();

        // CA par mois (12 derniers mois)
        $query = "SELECT DATE_FORMAT(date_creation, '%Y-%m') as mois,
                        SUM(prix_total) as ca
                    FROM commande
                    WHERE statut != 'annulee'
                    AND date_creation >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                    GROUP BY DATE_FORMAT(date_creation, '%Y-%m')
                    ORDER BY mois ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $ca_par_mois = $stmt->fetchAll();

        // Top 5 menus les plus commandés
        $query = "SELECT m.titre, COUNT(c.id_commande) as nb_commandes
                    FROM commande c
                    INNER JOIN menu m ON c.id_menu = m.id_menu
                    GROUP BY m.id_menu
                    ORDER BY nb_commandes DESC
                    LIMIT 5";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $top_menus = $stmt->fetchAll();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // Liste de toutes les commandes
    public function orders() {
        $db = new Database();
        $conn = $db->getConnection();
        $orderModel = new Order($conn);

        $orders = $orderModel->getAll();

        require_once __DIR__ . '/../views/admin/orders.php';
    }

    // Détail d'une commande
    public function orderDetail($order_id) {
        $db = new Database();
        $conn = $db->getConnection();
        $orderModel = new Order($conn);

        $order = $orderModel->getById($order_id);

        if (!$order) {
            $_SESSION['error'] = 'Commande introuvable';
            header('Location: /admin/orders');
            exit;
        }

        require_once __DIR__ . '/../views/admin/order-detail.php';
    }

    // Changer le statut d'une commande - AVEC ENVOI EMAIL
    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/orders');
            exit;
        }

        $order_id = $_POST['order_id'] ?? null;
        $new_status = $_POST['statut'] ?? null;

        if (!$order_id || !$new_status) {
            $_SESSION['error'] = 'Donnees invalides';
            header('Location: /admin/orders');
            exit;
        }

        $db = new Database();
        $conn = $db->getConnection();
        $orderModel = new Order($conn);

        if ($orderModel->updateStatus($order_id, $new_status)) {
            // Récupérer les infos complètes de la commande
            $order = $orderModel->getById($order_id);
            
            // Récupérer les infos du menu
            $menuModel = new Menu($conn);
            $menu = $menuModel->getById($order['id_menu']);
            
            // Récupérer les infos de l'utilisateur
            $userQuery = "SELECT * FROM utilisateur WHERE id_utilisateur = :id";
            $stmt = $conn->prepare($userQuery);
            $stmt->bindParam(':id', $order['id_utilisateur']);
            $stmt->execute();
            $user = $stmt->fetch();
            
            // Envoyer l'email
            require_once __DIR__ . '/../helpers/Email.php';
            require_once __DIR__ . '/../helpers/EmailTemplates.php';
            
            $email = new Email();
            $content = EmailTemplates::changementStatut($order, $menu, $user, $new_status);
            
            $email->send(
                $user['email'],
                'Mise à jour de votre commande - Vite & Gourmand',
                $email->getTemplate($content)
            );
            
            $_SESSION['success'] = 'Statut mis a jour avec succes et email envoye au client';
        } else {
            $_SESSION['error'] = 'Erreur lors de la mise a jour';
        }

        header('Location: /admin/orderDetail/' . $order_id);
        exit;
    }
$email->send(
                    $user['email'],
                    'Mise à jour de votre commande - Vite & Gourmand',
                    $email->getTemplate($content)
                );
            }
            
            // Créer une notification pour le client
            require_once __DIR__ . '/../models/Notification.php';
            $notifModel = new Notification($conn);
            
            $status_labels = [
                'en_attente' => 'En attente',
                'accepte' => 'Acceptée',
                'en_preparation' => 'En préparation',
                'terminee' => 'Terminée',
                'annulee' => 'Annulée'
            ];
            
            $notifModel->create([
                'id_utilisateur' => $order['id_utilisateur'],
                'type' => 'changement_statut',
                'titre' => '📦 Commande #' . $order_id . ' : ' . $status_labels[$new_status],
                'message' => 'Votre commande a été mise à jour : ' . $status_labels[$new_status],
                'id_reference' => $order_id,
                'url' => '/order/confirmation/' . $order_id
            ]);


    // Liste des utilisateurs
    public function users() {
        $db = new Database();
        $conn = $db->getConnection();

        $query = "SELECT * FROM utilisateur ORDER BY date_creation DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll();

        require_once __DIR__ . '/../views/admin/users.php';
    }
}