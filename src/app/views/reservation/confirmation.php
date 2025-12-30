<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-4">📅 Mes réservations</h2>
    
    <?php if (empty($reservations)): ?>
        <div class="alert alert-info text-center">
            <h5>Aucune réservation</h5>
            <p>Vous n'avez pas encore effectué de réservation.</p>
            <a href="/menu" class="btn btn-primary">Découvrir nos menus</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservations as $resa): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header <?= $resa['statut'] === 'confirmee' ? 'bg-success' : ($resa['statut'] === 'annulee' ? 'bg-danger' : 'bg-warning') ?> text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Réservation #<?= $resa['id_reservation'] ?></span>
                                <span class="badge bg-white text-dark">
                                    <?php
                                    $statuts = [
                                        'en_attente' => 'En attente',
                                        'confirmee' => 'Confirmée',
                                        'annulee' => 'Annulée'
                                    ];
                                    echo $statuts[$resa['statut']] ?? $resa['statut'];
                                    ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($resa['menu_titre']) ?></h5>
                            
                            <div class="mb-2">
                                <strong>📅 Date :</strong> 
                                <?= date('d/m/Y', strtotime($resa['date_reservation'])) ?>
                            </div>
                            
                            <div class="mb-2">
                                <strong>🕐 Horaire :</strong> 
                                <?= date('H:i', strtotime($resa['heure_debut'])) ?> - <?= date('H:i', strtotime($resa['heure_fin'])) ?>
                            </div>
                            
                            <div class="mb-2">
                                <strong>👥 Personnes :</strong> 
                                <?= $resa['nb_personnes'] ?>
                            </div>
                            
                            <div class="mb-3">
                                <strong>💰 Total :</strong> 
                                <span class="text-primary fw-bold"><?= number_format($resa['prix_total'], 2, ',', ' ') ?> €</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex gap-2">
                                <a href="/reservation/confirmation/<?= $resa['id_reservation'] ?>" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    Voir détails
                                </a>
                                
                                <?php if ($resa['statut'] === 'confirmee' && strtotime($resa['date_reservation']) > time()): ?>
                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) { window.location.href='/reservation/cancel/<?= $resa['id_reservation'] ?>'; }">
                                        Annuler
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer text-muted small">
                            Réservée le <?= date('d/m/Y', strtotime($resa['date_creation'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>