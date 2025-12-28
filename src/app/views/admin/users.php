<?php 
$page_title = 'Gestion des Utilisateurs';
ob_start(); 
?>

<div class="card">
    <div class="card-header">
        <h5>👥 Tous les utilisateurs</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom complet</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Role</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user['id_utilisateur'] ?></td>
                            <td><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['gsm']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'employe' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($user['role']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?= $user['actif'] ? 'success' : 'danger' ?>">
                                    <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($user['date_creation'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#userModal<?= $user['id_utilisateur'] ?>">
                                    Voir details
                                </button>
                            </td>
                        </tr>

                        <!-- Modal détails utilisateur -->
                        <div class="modal fade" id="userModal<?= $user['id_utilisateur'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            👤 <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                                        <p><strong>Telephone :</strong> <?= htmlspecialchars($user['gsm']) ?></p>
                                        <p><strong>Adresse :</strong><br><?= nl2br(htmlspecialchars($user['adresse_postale'])) ?></p>
                                        <p><strong>Role :</strong> <?= ucfirst($user['role']) ?></p>
                                        <p><strong>Statut :</strong> <?= $user['actif'] ? 'Actif' : 'Inactif' ?></p>
                                        <p><strong>Membre depuis :</strong> <?= date('d/m/Y', strtotime($user['date_creation'])) ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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