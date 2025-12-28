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
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/user/dashboard">Mon espace</a></li>
                    <li class="breadcrumb-item"><a href="/user/orders">Mes commandes</a></li>
                    <li class="breadcrumb-item active">Commande #<?= $order['id_commande'] ?></li>
                </ol>
            </nav>

            <h1 class="mb-4">Commande #<?= $order['id_commande'] ?></h1>

            <!-- Statut -->
            <div class="card mb-4">
                <div class="card-body">
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Statut de la commande</h5>
                            <small class="text-muted">Commande passee le <?= date('d/m/Y a H:i', strtotime($order['date_creation'])) ?></small>
                        </div>
                        <span class="badge bg-<?= $color ?> fs-5">
                            <?= ucfirst(str_replace('_', ' ', $order['statut'])) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Détails du menu -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">🍽️ Menu commande</h5>
                </div>
                <div class="card-body">
                    <h4><?= htmlspecialchars($order['menu_titre']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($order['description']) ?></p>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>👥 Nombre de personnes :</strong> <?= $order['nb_personnes'] ?>
                        </div>
                        <div class="col-md-6">
                            <strong>💰 Prix de base :</strong> <?= number_format($order['prix_base'], 2) ?> €
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de livraison -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📍 Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>📅 Date de livraison :</strong><br>
                            <?= date('d/m/Y', strtotime($order['date_livraison'])) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>🕐 Heure de livraison :</strong><br>
                            <?= date('H:i', strtotime($order['heure_livraison'])) ?>
                        </div>
                        <div class="col-md-12">
                            <strong>📍 Adresse de livraison :</strong><br>
                            <?= nl2br(htmlspecialchars($order['adresse_livraison'])) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Récapitulatif des prix -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">💳 Recapitulatif des prix</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Prix du menu :</span>
                        <strong><?= number_format($order['prix_menu'], 2) ?> €</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frais de livraison :</span>
                        <strong><?= number_format($order['prix_livraison'], 2) ?> €</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5>Total :</h5>
                        <h5 class="text-success"><?= number_format($order['prix_total'], 2) ?> €</h5>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="d-grid gap-2">
                <a href="/user/orders" class="btn btn-outline-secondary">
                    ← Retour a mes commandes
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>