<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="display-4">🍽️ Nos Menus</h1>
            <p class="lead">Decouvrez tous nos menus pour vos evenements</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>🔍 Filtrer par :</h5>
                    <div class="row">
                        <!-- Filtre Thème -->
                        <div class="col-md-6">
                            <label class="form-label">Theme</label>
                            <select class="form-select" onchange="window.location.href='/menu?theme='+this.value">
                                <option value="">Tous les themes</option>
                                <?php foreach ($themes as $theme): ?>
                                    <option value="<?= $theme['id_theme'] ?>"
                                            <?= (isset($_GET['theme']) && $_GET['theme'] == $theme['id_theme']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($theme['nom_theme']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filtre Régime -->
                        <div class="col-md-6">
                            <label class="form-label">Regime alimentaire</label>
                            <select class="form-select" onchange="window.location.href='/menu?regime='+this.value">
                                <option value="">Tous les regimes</option>
                                <?php foreach ($regimes as $regime): ?>
                                    <option value="<?= $regime['id_regime'] ?>"
                                            <?= (isset($_GET['regime']) && $_GET['regime'] == $regime['id_regime']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($regime['nom_regime']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <?php if (isset($_GET['theme']) || isset($_GET['regime'])): ?>
                        <div class="mt-3">
                            <a href="/menu" class="btn btn-sm btn-outline-secondary">Reinitialiser les filtres</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des menus -->
    <div class="row">
        <?php if (!empty($menus)): ?>
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow">
                        <?php
                        // Récupérer la première image du menu
                        $images = $menuModel->getImages($menu['id_menu']);
                        $image_url = !empty($images) ? $images[0]['chemin_fichier'] : '/uploads/menus/default-menu.jpg';
                        ?>
                        <div class="position-relative">
                            <img src="<?= $image_url ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['titre']) ?>" style="height: 250px; object-fit: cover;">

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php
                                // Vérifier si c'est un favori
                                $favoriModel = new Favori($conn);
                                $isFavorite = $favoriModel->isFavorite($_SESSION['user_id'], $menu['id_menu']);
                                ?>
                                <button class="btn position-absolute top-0 end-0 m-2 favorite-btn <?= $isFavorite ? 'btn-danger' : 'btn-outline-light' ?>"
                                        data-menu-id="<?= $menu['id_menu'] ?>"
                                        style="border-radius: 50%; width: 40px; height: 40px; padding: 0;">
                                    <?= $isFavorite ? '❤️' : '🤍' ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h5>
                                <?php if ($menu['stock_disponible'] > 0): ?>
                                    <span class="badge bg-success">Disponible</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rupture</span>
                                <?php endif; ?>
                            </div>

                            <p class="card-text text-muted small">
                                <?= htmlspecialchars(substr($menu['description'], 0, 120)) ?>...
                            </p>

                            <div class="mb-2">
                                <?php if ($menu['nom_theme']): ?>
                                    <span class="badge bg-primary"><?= htmlspecialchars($menu['nom_theme']) ?></span>
                                <?php endif; ?>
                                <?php if ($menu['nom_regime']): ?>
                                    <span class="badge bg-info"><?= htmlspecialchars($menu['nom_regime']) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="text-primary mb-0"><?= number_format($menu['prix_base'], 2) ?> €</h4>
                                    <small class="text-muted">A partir de <?= $menu['nb_personnes_min'] ?> pers.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white">
                            <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-primary w-100">
                                Voir details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h5>Aucun menu trouve</h5>
                    <p>Essayez de modifier vos filtres</p>
                    <a href="/menu" class="btn btn-primary">Voir tous les menus</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script pour gérer les favoris -->
<script>
document.querySelectorAll('.favorite-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const menuId = this.dataset.menuId;
        const button = this;

        fetch('/favori/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'menu_id=' + menuId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.action === 'added') {
                    button.classList.remove('btn-outline-light');
                    button.classList.add('btn-danger');
                    button.textContent = '❤️';
                } else {
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-outline-light');
                    button.textContent = '🤍';
                }

                // Toast
                if (window.showToast) {
                    showToast(data.message, 'success');
                }
            }
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>