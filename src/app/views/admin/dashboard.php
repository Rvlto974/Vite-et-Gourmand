<?php 
$page_title = 'Dashboard Admin';
ob_start(); 
?>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Commandes</h6>
                <h2><?= $stats['total_commandes'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Chiffre d'Affaires</h6>
                <h2><?= number_format($stats['chiffre_affaires'], 2) ?> €</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Total Utilisateurs</h6>
                <h2><?= $stats['total_utilisateurs'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Total Menus</h6>
                <h2><?= $stats['total_menus'] ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Commandes par statut -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>📊 Commandes par statut</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <?php foreach ($stats['commandes_par_statut'] as $stat): ?>
                            <tr>
                                <td><?= ucfirst(str_replace('_', ' ', $stat['statut'])) ?></td>
                                <td class="text-end"><strong><?= $stat['nombre'] ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>⚡ Actions rapides</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/admin/orders" class="btn btn-primary">
                        📦 Voir toutes les commandes
                    </a>
                    <a href="/admin/users" class="btn btn-info">
                        👥 Gerer les utilisateurs
                    </a>
                    <a href="/menu" class="btn btn-success">
                        🍽️ Voir les menus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières commandes -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>📦 Dernieres commandes</h5>
        <a href="/admin/orders" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client</th>
                        <th>Menu</th>
                        <th>Date livraison</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dernieres_commandes as $order): ?>
                        <tr>
                            <td><?= $order['id_commande'] ?></td>
                            <td><?= htmlspecialchars($order['prenom'] . ' ' . $order['nom']) ?></td>
                            <td><?= htmlspecialchars($order['menu_titre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($order['date_livraison'])) ?></td>
                            <td><?= number_format($order['prix_total'], 2) ?> €</td>
                            <td>
                                <?php
                                $badge_colors = [
                                    'en_attente' => 'warning',
                                    'accepte' => 'info',
                                    'en_preparation' => 'primary',
                                    'en_livraison' => 'primary',
                                    'livre' => 'success',
                                    'terminee' => 'success',
                                    'annulee' => 'danger'
                                ];
                                $color = $badge_colors[$order['statut']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $color ?>">
                                    <?= ucfirst(str_replace('_', ' ', $order['statut'])) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/orderDetail/<?= $order['id_commande'] ?>" class="btn btn-sm btn-outline-primary">
                                    Voir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>