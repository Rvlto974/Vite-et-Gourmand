<?php 
$page_title = 'Gestion des Commandes';
ob_start(); 
?>

<div class="card">
    <div class="card-header">
        <h5>📦 Toutes les commandes</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date commande</th>
                        <th>Client</th>
                        <th>Menu</th>
                        <th>Nb personnes</th>
                        <th>Date livraison</th>
                        <th>Prix total</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><strong>#<?= $order['id_commande'] ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($order['date_creation'])) ?></td>
                                <td>
                                    <?= htmlspecialchars($order['prenom'] . ' ' . $order['nom']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($order['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($order['menu_titre']) ?></td>
                                <td><?= $order['nb_personnes'] ?> pers.</td>
                                <td>
                                    <?= date('d/m/Y', strtotime($order['date_livraison'])) ?><br>
                                    <small><?= date('H:i', strtotime($order['heure_livraison'])) ?></small>
                                </td>
                                <td><strong><?= number_format($order['prix_total'], 2) ?> €</strong></td>
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
                                    <a href="/admin/orderDetail/<?= $order['id_commande'] ?>" class="btn btn-sm btn-primary">
                                        Voir details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-0">Aucune commande pour le moment</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>