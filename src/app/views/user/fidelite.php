<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-4">🎁 Mon Programme Fidélité</h2>
    
    <!-- Carte Niveau actuel -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center py-5">
                    <div style="font-size: 5rem;"><?= $avantages['icon'] ?></div>
                    <h2 class="display-4 fw-bold"><?= strtoupper($avantages['nom']) ?></h2>
                    <p class="lead mb-0"><?= number_format($compte['points_total']) ?> points</p>
                </div>
            </div>
        </div>
        
        <!-- Progression -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">📈 Progression</h5>
                    
                    <?php if ($progression['prochain_niveau']): ?>
                        <p>Encore <strong><?= $progression['points_restants'] ?> points</strong> pour atteindre le niveau <strong><?= ucfirst($progression['prochain_niveau']) ?></strong></p>
                        
                        <div class="progress mb-3" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: <?= $progression['pourcentage'] ?>%"
                                 aria-valuenow="<?= $progression['pourcentage'] ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <?= $progression['pourcentage'] ?>%
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <strong>🎉 Félicitations !</strong> Vous avez atteint le niveau maximum !
                        </div>
                    <?php endif; ?>
                    
                    <small class="text-muted">💡 Gagnez 10 points par euro dépensé</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Avantages du niveau -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✨ Vos avantages <?= $avantages['nom'] ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6">💰</div>
                    <h6>Réduction</h6>
                    <p class="text-success fw-bold"><?= $avantages['reduction'] ?>%</p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6"><?= $avantages['livraison_gratuite'] ? '✅' : '❌' ?></div>
                    <h6>Livraison gratuite</h6>
                    <p><?= $avantages['livraison_gratuite'] ? 'Incluse' : 'Non incluse' ?></p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6"><?= $avantages['priorite'] ? '✅' : '❌' ?></div>
                    <h6>Traitement prioritaire</h6>
                    <p><?= $avantages['priorite'] ? 'Activé' : 'Non activé' ?></p>
                </div>
                <div class="col-md-3 text-center mb-3">
                    <div class="display-6"><?= $avantages['acces_exclusif'] ? '✅' : '❌' ?></div>
                    <h6>Accès exclusif</h6>
                    <p><?= $avantages['acces_exclusif'] ? 'Activé' : 'Non activé' ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tous les niveaux -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">🎯 Tous les niveaux</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php 
                $niveaux = ['bronze', 'argent', 'or', 'platine'];
                $points_requis = [0, 1000, 2500, 5000];
                foreach ($niveaux as $index => $niveau):
                    $info = Fidelite::getAvantages($niveau);
                    $is_current = $niveau === $compte['niveau'];
                ?>
                    <div class="col-md-3 mb-3">
                        <div class="card <?= $is_current ? 'border-primary' : '' ?> h-100">
                            <div class="card-body text-center">
                                <div style="font-size: 3rem;"><?= $info['icon'] ?></div>
                                <h5><?= $info['nom'] ?></h5>
                                <p class="text-muted small"><?= number_format($points_requis[$index]) ?>+ points</p>
                                <p class="mb-1"><small>Réduction: <?= $info['reduction'] ?>%</small></p>
                                <?php if ($is_current): ?>
                                    <span class="badge bg-primary">Votre niveau</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Historique des points -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">📜 Historique des points</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($historique)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Points</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historique as $item): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($item['date_creation'])) ?></td>
                                    <td><?= htmlspecialchars($item['description']) ?></td>
                                    <td>
                                        <span class="badge <?= $item['type'] === 'gain' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $item['type'] === 'gain' ? '+' : '-' ?><?= $item['points'] ?>
                                        </span>
                                    </td>
                                    <td><?= $item['type'] === 'gain' ? '📈 Gain' : '📉 Utilisation' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">Aucun historique pour le moment. Passez votre première commande pour gagner des points !</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>