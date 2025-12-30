<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <h2 class="mb-4">⭐ Mes favoris</h2>
    
    <?php if (empty($favoris)): ?>
        <div class="alert alert-info">
            <h5>Aucun favori pour le moment</h5>
            <p>Parcourez nos menus et cliquez sur le cœur ❤️ pour ajouter vos menus préférés !</p>
            <a href="/menu" class="btn btn-primary">Découvrir nos menus</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favoris as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="<?= $menu['image'] ?? '/uploads/menus/default.jpg' ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($menu['titre']) ?>"
                                 style="height: 200px; object-fit: cover;">
                            
                            <!-- Bouton favori -->
                            <button class="btn btn-danger position-absolute top-0 end-0 m-2 favorite-btn" 
                                    data-menu-id="<?= $menu['id_menu'] ?>"
                                    style="border-radius: 50%; width: 40px; height: 40px;">
                                ❤️
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars(substr($menu['description'], 0, 100)) ?>...</p>
                            
                            <div class="mb-3">
                                <?php if ($menu['nom_theme']): ?>
                                    <span class="badge bg-primary me-1"><?= htmlspecialchars($menu['nom_theme']) ?></span>
                                <?php endif; ?>
                                <?php if ($menu['nom_regime']): ?>
                                    <span class="badge bg-info"><?= htmlspecialchars($menu['nom_regime']) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h4 text-primary mb-0"><?= number_format($menu['prix_base'], 2) ?> €</span>
                                <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-outline-primary">Voir détails</a>
                            </div>
                            
                            <small class="text-muted d-block mt-2">
                                Ajouté le <?= date('d/m/Y', strtotime($menu['date_ajout'])) ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
                // Retirer la carte de la page
                button.closest('.col-md-4').remove();
                
                // Afficher message si plus de favoris
                if (document.querySelectorAll('.col-md-4').length === 0) {
                    location.reload();
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