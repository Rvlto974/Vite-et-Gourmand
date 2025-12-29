<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menu">Menus</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($menu['titre']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Colonne gauche - Info menu -->
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">

<!-- Carrousel d'images -->
                    <?php if (!empty($images)): ?>
                        <div id="menuCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach ($images as $index => $img): ?>
                                    <button type="button" data-bs-target="#menuCarousel" data-bs-slide-to="<?= $index ?>" 
                                            <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?>></button>
                                <?php endforeach; ?>
                            </div>
                            <div class="carousel-inner">
                                <?php foreach ($images as $index => $img): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= $img['chemin_fichier'] ?>" class="d-block w-100" alt="<?= htmlspecialchars($menu['titre']) ?>" 
                                            style="max-height: 400px; object-fit: cover;">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#menuCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#menuCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Titre et badges -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h1 class="display-5"><?= htmlspecialchars($menu['titre']) ?></h1>
                        <?php if ($menu['stock_disponible'] > 0): ?>
                            <span class="badge bg-success fs-6">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger fs-6">Rupture de stock</span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <p class="lead"><?= htmlspecialchars($menu['description']) ?></p>

                    <!-- Badges thème et régime -->
                    <div class="mb-4">
                        <?php if ($menu['nom_theme']): ?>
                            <span class="badge bg-primary fs-6 me-2">🎉 <?= htmlspecialchars($menu['nom_theme']) ?></span>
                        <?php endif; ?>
                        <?php if ($menu['nom_regime']): ?>
                            <span class="badge bg-info fs-6">🥗 <?= htmlspecialchars($menu['nom_regime']) ?></span>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <!-- Composition du menu -->
                    <h3 class="mb-4">📋 Composition du menu</h3>

                    <?php if (!empty($entrees)): ?>
                        <div class="mb-4">
                            <h5 class="text-primary">🥗 Entrees</h5>
                            <ul class="list-group">
                                <?php foreach ($entrees as $plat): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($plat['nom_plat']) ?></strong>
                                        <?php if ($plat['description']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($plat['description']) ?></small>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($plats_principaux)): ?>
                        <div class="mb-4">
                            <h5 class="text-primary">🍖 Plats principaux</h5>
                            <ul class="list-group">
                                <?php foreach ($plats_principaux as $plat): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($plat['nom_plat']) ?></strong>
                                        <?php if ($plat['description']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($plat['description']) ?></small>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($desserts)): ?>
                        <div class="mb-4">
                            <h5 class="text-primary">🍰 Desserts</h5>
                            <ul class="list-group">
                                <?php foreach ($desserts as $plat): ?>
                                    <li class="list-group-item">
                                        <strong><?= htmlspecialchars($plat['nom_plat']) ?></strong>
                                        <?php if ($plat['description']): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($plat['description']) ?></small>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Conditions -->
                    <?php if ($menu['conditions']): ?>
                        <div class="alert alert-info">
                            <h6>ℹ️ Conditions</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($menu['conditions'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite - Commande -->
        <div class="col-md-4">
            <div class="card shadow sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h3 class="text-center mb-4">Commander</h3>

                    <!-- Prix -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary mb-0"><?= number_format($menu['prix_base'], 2) ?> €</h2>
                        <small class="text-muted">Prix de base</small>
                    </div>

                    <!-- Info personnes -->
                    <div class="alert alert-warning">
                        <small>
                            <strong>👥 A partir de <?= $menu['nb_personnes_min'] ?> personnes</strong>
                        </small>
                    </div>

                    <!-- Stock -->
                    <?php if ($menu['stock_disponible'] > 0): ?>
                        <div class="mb-3">
                            <small class="text-success">
                                ✅ Stock disponible : <?= $menu['stock_disponible'] ?> menu(s)
                            </small>
                        </div>
                    <?php endif; ?>

                    <!-- Boutons d'action -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($menu['stock_disponible'] > 0): ?>
                            <a href="/order/create/<?= $menu['id_menu'] ?>" class="btn btn-success btn-lg w-100 mb-2">
                                🛒 Commander ce menu
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-lg w-100 mb-2" disabled>
                                Rupture de stock
                            </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/auth/login" class="btn btn-primary btn-lg w-100 mb-2">
                            Connectez-vous pour commander
                        </a>
                        <a href="/auth/register" class="btn btn-outline-success btn-lg w-100">
                            Creer un compte
                        </a>
                    <?php endif; ?>

                    <hr>

                    <!-- Retour -->
                    <a href="/menu" class="btn btn-outline-secondary w-100">
                        ← Retour aux menus
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section Avis -->
    <?php
    // Récupérer les avis du menu
    $avisModel = new Avis($conn);
    $avis_menu = $avisModel->getByMenu($menu['id_menu']);
    $rating = $avisModel->getAverageRating($menu['id_menu']);
    ?>
    
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">⭐ Avis clients</h4>
                        <?php if ($rating['total'] > 0): ?>
                            <div>
                                <span style="color: #ffc107; font-size: 1.5rem;">
                                    <?= str_repeat('★', round($rating['moyenne'])) . str_repeat('☆', 5 - round($rating['moyenne'])) ?>
                                </span>
                                <span class="ms-2">
                                    <?= number_format($rating['moyenne'], 1) ?>/5 
                                    <small class="text-muted">(<?= $rating['total'] ?> avis)</small>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        $userHasReviewed = $avisModel->userHasReviewed($_SESSION['user_id'], $menu['id_menu']);
                        $userCanReview = $avisModel->userCanReview($_SESSION['user_id'], $menu['id_menu']);
                        ?>
                        
                        <?php if (!$userHasReviewed && $userCanReview): ?>
                            <div class="alert alert-info">
                                <strong>Vous avez commande ce menu ?</strong>
                                <a href="/avis/create/<?= $menu['id_menu'] ?>" class="btn btn-sm btn-warning ms-2">
                                    Laisser un avis
                                </a>
                            </div>
                        <?php elseif ($userHasReviewed): ?>
                            <div class="alert alert-success">
                                ✅ Vous avez deja laisse un avis pour ce menu
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($avis_menu)): ?>
                        <?php foreach ($avis_menu as $avis): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong><?= htmlspecialchars($avis['prenom']) ?></strong>
                                        <span style="color: #ffc107; font-size: 1.2rem; margin-left: 10px;">
                                            <?= str_repeat('★', $avis['note']) . str_repeat('☆', 5 - $avis['note']) ?>
                                        </span>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($avis['date_creation'])) ?>
                                    </small>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($avis['commentaire'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <p>Aucun avis pour le moment</p>
                            <?php if (isset($_SESSION['user_id']) && !$userHasReviewed && $userCanReview): ?>
                                <a href="/avis/create/<?= $menu['id_menu'] ?>" class="btn btn-warning">
                                    Soyez le premier a laisser un avis !
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
/* Carrousel amélioré */
.carousel-control-prev,
.carousel-control-next {
    width: 8% !important;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(255, 255, 255, 0.3) !important;
    backdrop-filter: blur(5px);
    border: 2px solid rgba(255, 255, 255, 0.8);
    border-radius: 50% !important;
    padding: 15px !important;
    width: 45px !important;
    height: 45px !important;
    transition: all 0.3s ease;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
    background-color: rgba(255, 255, 255, 0.5) !important;
    transform: scale(1.15) !important;
    border-color: rgba(255, 255, 255, 1);
}

.carousel-indicators {
    bottom: -40px !important;
}

.carousel-indicators button {
    width: 12px !important;
    height: 12px !important;
    border-radius: 50% !important;
    background-color: rgba(108, 117, 125, 0.5) !important;
    border: 2px solid rgba(108, 117, 125, 0.3);
    margin: 0 5px !important;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    background-color: #0d6efd !important;
    border-color: #0d6efd;
    transform: scale(1.2) !important;
}

.carousel {
    margin-bottom: 50px;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>