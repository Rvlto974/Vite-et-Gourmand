<?php

class NotificationController {
    
    // Stream SSE pour les notifications en temps réel
    public function stream() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            exit;
        }
        
        // Headers SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx
        
        $db = new Database();
        $conn = $db->getConnection();
        $notifModel = new Notification($conn);
        
        $last_id = $_GET['last_id'] ?? 0;
        
        // Vérifier les nouvelles notifications
        $new_notifications = $notifModel->getNewSince($_SESSION['user_id'], $last_id);
        
        if (!empty($new_notifications)) {
            foreach ($new_notifications as $notif) {
                echo "data: " . json_encode($notif) . "\n\n";
                flush();
            }
        } else {
            // Heartbeat toutes les 15 secondes
            echo ": heartbeat\n\n";
            flush();
        }
        
        exit;
    }
    
    // Page liste des notifications
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vous devez être connecté';
            header('Location: /auth/login');
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $notifModel = new Notification($conn);
        
        $notifications = $notifModel->getByUser($_SESSION['user_id'], 50);
        
        require_once __DIR__ . '/../views/user/notifications.php';
    }
    
    // Marquer comme lue (AJAX)
    public function markAsRead() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $notif_id = $_POST['notif_id'] ?? null;
        
        if (!$notif_id) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $notifModel = new Notification($conn);
        
        $success = $notifModel->markAsRead($notif_id);
        
        echo json_encode(['success' => $success]);
        exit;
    }
    
    // Marquer toutes comme lues
    public function markAllAsRead() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $notifModel = new Notification($conn);
        
        $notifModel->markAllAsRead($_SESSION['user_id']);
        
        header('Location: /notification/index');
        exit;
    }
    
    // Compter les non lues (AJAX)
    public function countUnread() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['count' => 0]);
            exit;
        }
        
        $db = new Database();
        $conn = $db->getConnection();
        $notifModel = new Notification($conn);
        
        $count = $notifModel->countUnread($_SESSION['user_id']);
        
        echo json_encode(['count' => $count]);
        exit;
    }
}