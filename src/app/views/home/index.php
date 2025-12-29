<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Hero Section -->
<div class="hero-section text-white py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container text-center">
        <h1 class="display-3 fw-bold mb-4">Bienvenue chez Vite & Gourmand</h1>
        <p class="lead mb-4">Votre traiteur événementiel à Bordeaux depuis plus de 25 ans</p>
        <p class="fs-5 mb-4">Des menus raffinés pour tous vos événements : mariages, anniversaires, repas d'entreprise...</p>
        <a href="/menu" class="btn btn-warning btn-lg px-5 py-3 fw-bold">
            🍽️ Découvrir nos menus
        </a>
    </div>
</div>

<!-- Section Menus Populaires -->
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">🔥 Nos menus populaires</h2>
        <p class="lead text-muted">Les préférés de nos clients</p>
    </div>
    
    <?php 
    // DEBUG
    echo "<pre>";
    echo "Nombre de menus dans le tableau: " . count($top_menus) . "\n";
    foreach ($top_menus as $index => $menu) {
        echo "Menu[$index]: ID={$menu['id_menu']}, Titre={$menu['titre']}\n";
    }
    echo "</pre>";
    // FIN DEBUG
    ?>
    
    <div class="row">
        <?php if (!empty($top_menus)): ?>
            <?php foreach ($top_menus as $menu): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-lg border-0">
                        <div class="position-relative">
                            <img src="<?= $menu['image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['titre']) ?>" 
                                 style="height: 250px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 m-3 badge bg-danger fs-6">
                                ⭐ Populaire
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($menu['titre']) ?></h5>
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
                                <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-primary">Voir détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-muted">Aucun menu disponible pour le moment</p>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="/menu" class="btn btn-outline-primary btn-lg">Voir tous les menus →</a>
    </div>
</div>

<!-- Section Comment ça marche -->
<div class="bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">💡 Comment ça marche ?</h2>
            <p class="lead text-muted">4 étapes simples pour votre événement réussi</p>
        </div>
        
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            1️⃣
                        </div>
                        <h5 class="fw-bold">Choisissez votre menu</h5>
                        <p class="text-muted">Parcourez notre catalogue et sélectionnez le menu parfait pour votre événement</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="rounded-circle bg-success text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            2️⃣
                        </div>
                        <h5 class="fw-bold">Passez commande</h5>
                        <p class="text-muted">Indiquez le nombre de personnes, la date et validez votre commande en ligne</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="rounded-circle bg-warning text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            3️⃣
                        </div>
                        <h5 class="fw-bold">Nous préparons</h5>
                        <p class="text-muted">Nos chefs préparent votre menu avec des produits frais et de qualité</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 80px; height: 80px; font-size: 2rem;">
                            4️⃣
                        </div>
                        <h5 class="fw-bold">Livraison</h5>
                        <p class="text-muted">Nous livrons à l'heure prévue avec le matériel de service inclus</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Témoignages -->
<?php if (!empty($temoignages)): ?>
<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold">💬 Ils nous ont fait confiance</h2>
        <p class="lead text-muted">Les avis de nos clients</p>
    </div>
    
    <div class="row">
        <?php foreach ($temoignages as $temoignage): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <span style="color: #ffc107; font-size: 1.5rem;">
                                <?= str_repeat('★', $temoignage['note']) . str_repeat('☆', 5 - $temoignage['note']) ?>
                            </span>
                        </div>
                        <p class="card-text fst-italic">"<?= htmlspecialchars(substr($temoignage['commentaire'], 0, 150)) ?>..."</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($temoignage['prenom']) ?></strong>
                                <br><small class="text-muted"><?= htmlspecialchars($temoignage['menu_titre']) ?></small>
                            </div>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($temoignage['date_creation'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Call to Action Final -->
<div class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Prêt à organiser votre événement ?</h2>
        <p class="lead mb-4">Découvrez nos menus et passez commande en quelques clics</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="/menu" class="btn btn-warning btn-lg px-5">Voir les menus</a>
            <a href="/contact" class="btn btn-outline-light btn-lg px-5">Nous contacter</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>