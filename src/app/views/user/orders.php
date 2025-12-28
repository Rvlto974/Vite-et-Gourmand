<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">👤 Mon espace</h5>
                    <div class="list-group list-group-flush">
                        <a href="/user/dashboard" class="list-group-item list-group-item-action">
                            📊 Tableau de bord
                        </a>
                        <a href="/user/orders" class="list-group-item list-group-item-action active">
                            📦 Mes commandes
                        </a>
                        <a href="/user/profile" class="list-group-item list-group-item-action">
                            ⚙️ Mon profil
                        </a>
                        <a href="/auth/logout" class="list-group-item list-group-item-action text-danger">
                            🚪 Deconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-9">
            <h1 class="mb-4">📦 Mes commandes</h1>

            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <h5 class="text-muted mb-0">#<?= $order['id_commande'] ?></h5>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($order['date_creation'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1"><?= htmlspecialchars($order['menu_titre']) ?></h6>
                                    <small class="text-muted">
                                        <?= $order['nb_personnes'] ?> personnes
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <strong>Livraison :</strong><br>
                                    <small>
                                        <?= date('d/m/Y', strtotime($order['date_livraison'])) ?><br>
                                        <?= date('H:i', strtotime($order['heure_livraison'])) ?>
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <?php
                                    $badge_color = [
                                        'en_attente' => 'warning',
                                        'accepte' => 'info',
                                        'en_preparation' => 'primary',
                                        'en_livraison' => 'primary',
                                        'livre' => 'success',
                                        'terminee' => 'success',
                                        'annulee' => 'danger'
                                    ];
                                    $color = $badge_color[$order['statut']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?= $color ?> w-100">
                                        <?= ucfirst(str_replace('_', ' ', $order['statut'])) ?>
                                    </span>
                                    <h5 class="mt-2 mb-0"><?= number_format($order['prix_total'], 2) ?> €</h5>
                                </div>
                                <div class="col-md-2">
                                    <a href="/user/orderDetail/<?= $order['id_commande'] ?>" class="btn btn-outline-primary btn-sm w-100">
                                        Voir details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h3>Aucune commande</h3>
                        <p class="text-muted">Vous n'avez pas encore passe de commande</p>
                        <a href="/menu" class="btn btn-primary">Decouvrir nos menus</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>