<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-4">📊 Statistiques & Analytics</h2>
    
    <!-- Cartes statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Actions</h5>
                    <p class="display-4"><?= number_format($totalActions) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Types d'actions</h5>
                    <p class="display-4"><?= count($actionsByType) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Pages trackées</h5>
                    <p class="display-4"><?= count($topPages) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Graphique : Actions par jour -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">📈 Actions par jour (30 derniers jours)</h5>
        </div>
        <div class="card-body">
            <canvas id="actionsChart" height="80"></canvas>
        </div>
    </div>
    
    <div class="row">
        <!-- Pages les plus visitées -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🔥 Pages les plus visitées</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($topPages)): ?>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Page</th>
                                    <th class="text-end">Vues</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topPages as $page): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($page->_id) ?></td>
                                        <td class="text-end"><strong><?= $page->count ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Aucune donnée disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Actions par type -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">🎯 Actions par type</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($actionsByType)): ?>
                        <canvas id="actionsTypeChart" height="200"></canvas>
                    <?php else: ?>
                        <p class="text-muted">Aucune donnée disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique : Actions par jour
const ctx1 = document.getElementById('actionsChart');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [{
            label: 'Nombre d\'actions',
            data: <?= json_encode($chartData) ?>,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
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
                beginAtZero: true
            }
        }
    }
});

// Graphique : Actions par type
<?php if (!empty($actionsByType)): ?>
const ctx2 = document.getElementById('actionsTypeChart');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($a) => $a->_id, $actionsByType)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($a) => $a->count, $actionsByType)) ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)'
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
<?php endif; ?>
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>