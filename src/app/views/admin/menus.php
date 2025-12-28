<?php 
$page_title = 'Gestion des Menus';
ob_start(); 
?>

<div class="mb-3">
    <a href="/menu/adminCreate" class="btn btn-success">
        ➕ Ajouter un menu
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5>🍽️ Tous les menus</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Theme</th>
                        <th>Regime</th>
                        <th>Prix</th>
                        <th>Nb min</th>
                        <th>Stock</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($menus)): ?>
                        <?php foreach ($menus as $menu): ?>
                            <tr>
                                <td><?= $menu['id_menu'] ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($menu['titre']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars(substr($menu['description'], 0, 60)) ?>...</small>
                                </td>
                                <td>
                                    <?php if ($menu['nom_theme']): ?>
                                        <span class="badge bg-primary"><?= htmlspecialchars($menu['nom_theme']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($menu['nom_regime']): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($menu['nom_regime']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($menu['prix_base'], 2) ?> €</td>
                                <td><?= $menu['nb_personnes_min'] ?> pers.</td>
                                <td><?= $menu['stock_disponible'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $menu['actif'] ? 'success' : 'danger' ?>">
                                        <?= $menu['actif'] ? 'Actif' : 'Inactif' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-outline-info" target="_blank" title="Voir">
                                            👁️
                                        </a>
                                        <a href="/menu/adminEdit/<?= $menu['id_menu'] ?>" class="btn btn-outline-primary" title="Modifier">
                                            ✏️
                                        </a>
                                        <a href="/menu/adminDelete/<?= $menu['id_menu'] ?>" 
                                            class="btn btn-outline-danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Etes-vous sur de vouloir supprimer ce menu ?')">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <p class="text-muted mb-3">Aucun menu pour le moment</p>
                                <a href="/menu/adminCreate" class="btn btn-success">Creer le premier menu</a>
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