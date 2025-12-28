<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- Hero Section -->
    <div class="jumbotron bg-light p-5 rounded">
        <h1 class="display-4">🍽️ Bienvenue chez Vite & Gourmand</h1>
        <p class="lead">Votre traiteur événementiel à Bordeaux depuis 25 ans</p>
        <hr class="my-4">
        <p>Des menus savoureux et personnalisés pour tous vos événements</p>
        <a class="btn btn-primary btn-lg" href="/menu" role="button">Découvrir nos menus</a>
    </div>

    <!-- Nos Menus -->
    <div class="row my-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Nos Menus Populaires</h2>
        </div>
        
        <?php if (!empty($menus)): ?>
            <?php foreach ($menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($menu['titre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($menu['description'], 0, 100)) ?>...</p>
                            <p class="text-primary fw-bold"><?= number_format($menu['prix_base'], 2) ?> €</p>
                            <p class="text-muted"><small>À partir de <?= $menu['nb_personnes_min'] ?> personnes</small></p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="/menu/<?= $menu['id_menu'] ?>" class="btn btn-outline-primary btn-sm">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">Aucun menu disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Call to Action -->
    <div class="row my-5">
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3>📞 Besoin d'un devis ?</h3>
                    <p>Contactez-nous pour organiser votre événement</p>
                    <a href="/contact" class="btn btn-light">Nous contacter</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>👤 Créer un compte</h3>
                    <p>Passez commande en ligne facilement</p>
                    <a href="/auth/register" class="btn btn-light">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>