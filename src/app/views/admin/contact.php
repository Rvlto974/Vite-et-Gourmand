<?php 
$page_title = 'Messages de contact';
ob_start(); 
?>

<div class="card">
    <div class="card-header">
        <h5>📧 Tous les messages de contact</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $msg): ?>
                            <tr class="<?= !$msg['traite'] ? 'table-warning' : '' ?>">
                                <td><?= $msg['id_contact'] ?></td>
                                <td><?= htmlspecialchars($msg['email']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($msg['titre']) ?></strong>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars(substr($msg['message'], 0, 60)) ?>...</small>
                                    <button class="btn btn-sm btn-link p-0" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#messageModal<?= $msg['id_contact'] ?>">
                                        Lire plus
                                    </button>
                                </td>
                                <td>
                                    <?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $msg['traite'] ? 'success' : 'warning' ?>">
                                        <?= $msg['traite'] ? 'Traité' : 'En attente' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/contact/adminToggleStatus/<?= $msg['id_contact'] ?>" 
                                           class="btn btn-<?= $msg['traite'] ? 'warning' : 'success' ?>" 
                                           title="<?= $msg['traite'] ? 'Marquer non traité' : 'Marquer traité' ?>">
                                            <?= $msg['traite'] ? '↩️' : '✓' ?>
                                        </a>
                                        <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" 
                                           class="btn btn-info" 
                                           title="Répondre par email">
                                            ✉️
                                        </a>
                                        <a href="/contact/adminDelete/<?= $msg['id_contact'] ?>" 
                                           class="btn btn-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Etes-vous sur de vouloir supprimer ce message ?')">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal pour afficher le message complet -->
                            <div class="modal fade" id="messageModal<?= $msg['id_contact'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                📧 <?= htmlspecialchars($msg['titre']) ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>De :</strong> <?= htmlspecialchars($msg['email']) ?></p>
                                            <p><strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($msg['date_envoi'])) ?></p>
                                            <hr>
                                            <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="btn btn-primary">
                                                ✉️ Répondre
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Aucun message pour le moment</p>
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