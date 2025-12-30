<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🔔 Mes notifications</h2>
        <?php if (!empty($notifications) && array_filter($notifications, fn($n) => !$n['lue'])): ?>
            <a href="/notification/markAllAsRead" class="btn btn-outline-primary btn-sm">
                Tout marquer comme lu
            </a>
        <?php endif; ?>
    </div>
    
    <?php if (empty($notifications)): ?>
        <div class="alert alert-info text-center">
            <h5>Aucune notification</h5>
            <p class="mb-0">Vous serez notifié ici des mises à jour importantes</p>
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifications as $notif): ?>
                <div class="list-group-item <?= !$notif['lue'] ? 'list-group-item-primary' : '' ?>" 
                     data-notif-id="<?= $notif['id_notification'] ?>">
                    <div class="d-flex w-100 justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <?= htmlspecialchars($notif['titre']) ?>
                                <?php if (!$notif['lue']): ?>
                                    <span class="badge bg-primary ms-2">Nouveau</span>
                                <?php endif; ?>
                            </h6>
                            <p class="mb-1"><?= htmlspecialchars($notif['message']) ?></p>
                            <small class="text-muted">
                                <?php
                                $time = strtotime($notif['date_creation']);
                                $diff = time() - $time;
                                if ($diff < 60) {
                                    echo 'Il y a quelques secondes';
                                } elseif ($diff < 3600) {
                                    echo 'Il y a ' . floor($diff / 60) . ' min';
                                } elseif ($diff < 86400) {
                                    echo 'Il y a ' . floor($diff / 3600) . ' h';
                                } else {
                                    echo date('d/m/Y à H:i', $time);
                                }
                                ?>
                            </small>
                        </div>
                        <div class="ms-3">
                            <?php if ($notif['url']): ?>
                                <a href="<?= htmlspecialchars($notif['url']) ?>" 
                                   class="btn btn-sm btn-outline-primary"
                                   onclick="markAsRead(<?= $notif['id_notification'] ?>)">
                                    Voir
                                </a>
                            <?php endif; ?>
                            <?php if (!$notif['lue']): ?>
                                <button class="btn btn-sm btn-outline-secondary ms-1" 
                                        onclick="markAsRead(<?= $notif['id_notification'] ?>)">
                                    ✓
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function markAsRead(notifId) {
    fetch('/notification/markAsRead', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'notif_id=' + notifId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const item = document.querySelector(`[data-notif-id="${notifId}"]`);
            if (item) {
                item.classList.remove('list-group-item-primary');
                const badge = item.querySelector('.badge');
                if (badge) badge.remove();
                const btn = item.querySelector('.btn-outline-secondary');
                if (btn) btn.remove();
            }
            
            // Recharger le compteur
            if (typeof loadNotificationCount === 'function') {
                loadNotificationCount();
            }
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>