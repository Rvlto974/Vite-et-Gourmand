<?php 
$page_title = 'Gestion des Avis';
ob_start(); 
?>

<div class="card">
    <div class="card-header">
        <h5>⭐ Tous les avis</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Utilisateur</th>
                        <th>Menu</th>
                        <th>Note</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($avis)): ?>
                        <?php foreach ($avis as $av): ?>
                            <tr>
                                <td><?= $av['id_avis'] ?></td>
                                <td>
                                    <?= htmlspecialchars($av['prenom'] . ' ' . $av['nom']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($av['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($av['menu_titre']) ?></td>
                                <td>
                                    <span style="color: #ffc107; font-size: 1.2rem;">
                                        <?= str_repeat('★', $av['note']) . str_repeat('☆', 5 - $av['note']) ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars(substr($av['commentaire'], 0, 60)) ?>...</small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($av['date_creation'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $av['valide'] ? 'success' : 'warning' ?>">
                                        <?= $av['valide'] ? 'Validé' : 'En attente' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if (!$av['valide']): ?>
                                            <form action="/avis/adminUpdateStatus" method="POST" style="display:inline;">
                                                <input type="hidden" name="avis_id" value="<?= $av['id_avis'] ?>">
                                                <input type="hidden" name="statut" value="valide">
                                                <button type="submit" class="btn btn-success" title="Valider">✓</button>
                                            </form>
                                        <?php else: ?>
                                            <form action="/avis/adminUpdateStatus" method="POST" style="display:inline;">
                                                <input type="hidden" name="avis_id" value="<?= $av['id_avis'] ?>">
                                                <input type="hidden" name="statut" value="rejete">
                                                <button type="submit" class="btn btn-warning" title="Invalider">✗</button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <a href="/avis/adminDelete/<?= $av['id_avis'] ?>" 
                                            class="btn btn-danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Etes-vous sur de vouloir supprimer cet avis ?')">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun avis pour le moment</p>
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