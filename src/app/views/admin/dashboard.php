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

<!-- Graphiques -->
<div class="row mb-4">
    <!-- Graphique CA par mois -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>📈 Chiffre d'affaires (12 derniers mois)</h5>
            </div>
            <div class="card-body">
                <canvas id="caChart" height="80"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Graphique commandes par statut -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>🥧 Commandes par statut</h5>
            </div>
            <div class="card-body">
                <canvas id="statutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top menus -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>🏆 Top 5 menus les plus commandés</h5>
            </div>
            <div class="card-body">
                <canvas id="topMenusChart" height="60"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides et dernières commandes -->
<div class="row mb-4">
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
                    <a href="/menu/adminList" class="btn btn-warning">
                        🍽️ Gerer les menus
                    </a>
                    <a href="/avis/adminList" class="btn btn-success">
                        ⭐ Moderer les avis
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>📊 Statistiques détaillées</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Préparer les données PHP pour JavaScript
const caData = <?= json_encode($ca_par_mois) ?>;
const topMenusData = <?= json_encode($top_menus) ?>;
const statutData = <?= json_encode($stats['commandes_par_statut']) ?>;

// Graphique CA par mois
const caCtx = document.getElementById('caChart').getContext('2d');
new Chart(caCtx, {
    type: 'line',
    data: {
        labels: caData.map(item => {
            const [year, month] = item.mois.split('-');
            return new Date(year, month - 1).toLocaleDateString('fr-FR', { month: 'short', year: 'numeric' });
        }),
        datasets: [{
            label: 'Chiffre d\'affaires (€)',
            data: caData.map(item => parseFloat(item.ca)),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' €';
                    }
                }
            }
        }
    }
});

// Graphique commandes par statut (Doughnut)
const statutCtx = document.getElementById('statutChart').getContext('2d');
new Chart(statutCtx, {
    type: 'doughnut',
    data: {
        labels: statutData.map(item => item.statut.replace('_', ' ')),
        datasets: [{
            data: statutData.map(item => item.nombre),
            backgroundColor: [
                'rgba(255, 206, 86, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(255, 99, 132, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique top menus (Bar)
const topMenusCtx = document.getElementById('topMenusChart').getContext('2d');
new Chart(topMenusCtx, {
    type: 'bar',
    data: {
        labels: topMenusData.map(item => item.titre),
        datasets: [{
            label: 'Nombre de commandes',
            data: topMenusData.map(item => parseInt(item.nb_commandes)),
            backgroundColor: 'rgba(54, 162, 235, 0.8)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>