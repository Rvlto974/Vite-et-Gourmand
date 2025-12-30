<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🎁 Gestion des codes promo</h2>
        <a href="/promo/adminCreate" class="btn btn-success">
            ➕ Créer un code promo
        </a>
    </div>

    <?php if (empty($promos)): ?>
        <div class="alert alert-info">
            Aucun code promo pour le moment. Créez-en un !
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Valeur</th>
                        <th>Montant min.</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Utilisations</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($promos as $promo): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($promo['code']) ?></strong></td>
                            <td>
                                <?php if ($promo['type'] === 'pourcentage'): ?>
                                    <span class="badge bg-info">%</span>
                                <?php else: ?>
                                    <span class="badge bg-success">€</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($promo['type'] === 'pourcentage'): ?>
                                    <?= $promo['valeur'] ?> %
                                <?php else: ?>
                                    <?= number_format($promo['valeur'], 2) ?> €
                                <?php endif; ?>
                            </td>
                            <td><?= number_format($promo['montant_minimum'], 2) ?> €</td>
                            <td><?= date('d/m/Y', strtotime($promo['date_debut'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($promo['date_fin'])) ?></td>
                            <td>
                                <?= $promo['utilisation_actuelle'] ?> 
                                <?php if ($promo['utilisation_max']): ?>
                                    / <?= $promo['utilisation_max'] ?>
                                <?php else: ?>
                                    / ∞
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                $now = new DateTime();
                                $debut = new DateTime($promo['date_debut']);
                                $fin = new DateTime($promo['date_fin']);
                                $is_valid = $promo['actif'] && $now >= $debut && $now <= $fin;
                                ?>
                                <?php if ($is_valid): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/promo/adminDelete/<?= $promo['id_promo'] ?>" 
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Supprimer ce code promo ?')">
                                    🗑️
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>