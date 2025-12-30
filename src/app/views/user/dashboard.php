<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">👤 Mon espace</h5>
                    <div class="list-group list-group-flush">
                        <a href="/user/dashboard" class="list-group-item list-group-item-action active">
                            📊 Tableau de bord
                        </a>
                        <a href="/user/orders" class="list-group-item list-group-item-action">
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
            <!-- En-tête -->
            <div class="mb-4">
                <h1>Bonjour <?= htmlspecialchars($user['prenom']) ?> ! 👋</h1>
                <p class="text-muted">Bienvenue dans votre espace personnel</p>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total commandes</h6>
                            <h2><?= $stats['total_commandes'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Total depense</h6>
                            <h2><?= number_format($stats['total_depense'] ?? 0, 2) ?> €</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6 class="card-title">Derniere commande</h6>
                            <h6>
                                <?php if ($stats['derniere_commande']): ?>
                                    <?= date('d/m/Y', strtotime($stats['derniere_commande'])) ?>
                                <?php else: ?>
                                    Aucune
                                <?php endif; ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mes informations -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📋 Mes informations</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Nom complet :</strong><br>
                            <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email :</strong><br>
                            <?= htmlspecialchars($user['email']) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Telephone :</strong><br>
                            <?= htmlspecialchars($user['gsm']) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Role :</strong><br>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'employe' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <strong>Adresse :</strong><br>
                            <?= nl2br(htmlspecialchars($user['adresse_postale'])) ?>
                        </div>
                    </div>
                    <a href="/user/profile" class="btn btn-primary">Modifier mes informations</a>
                </div>
            </div>

            <!-- Dernières commandes -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">📦 Mes dernieres commandes</h5>
                    <a href="/user/orders" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Menu</th>
                                        <th>Date livraison</th>
                                        <th>Prix total</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                        <tr>
                                            <td><?= $order['id_commande'] ?></td>
                                            <td><?= htmlspecialchars($order['menu_titre']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($order['date_livraison'])) ?></td>
                                            <td><?= number_format($order['prix_total'], 2) ?> €</td>
                                            <td>
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
                                                <span class="badge bg-<?= $color ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $order['statut'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/user/orderDetail/<?= $order['id_commande'] ?>" class="btn btn-sm btn-outline-primary">
                                                    Voir
                                                </a>
                                                <a href="/order/downloadPdf/<?= $order['id_commande'] ?>" class="btn btn-sm btn-danger">
                                                    📄 PDF
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center">
                            <p class="mb-0">Vous n'avez pas encore passe de commande</p>
                            <a href="/menu" class="btn btn-primary mt-3">Decouvrir nos menus</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>