<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menu">Menus</a></li>
            <li class="breadcrumb-item"><a href="/menu/show/<?= $menu['id_menu'] ?>"><?= htmlspecialchars($menu['titre']) ?></a></li>
            <li class="breadcrumb-item active">Commander</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Formulaire de commande -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">🛒 Passer commande</h3>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form action="/order/store" method="POST" id="orderForm">
                        <input type="hidden" name="menu_id" value="<?= $menu['id_menu'] ?>">
                        
                        <!-- Menu sélectionné -->
                        <div class="alert alert-info">
                            <h5><?= htmlspecialchars($menu['titre']) ?></h5>
                            <p class="mb-0">Prix de base : <strong><?= number_format($menu['prix_base'], 2) ?> €</strong> (pour <?= $menu['nb_personnes_min'] ?> personnes minimum)</p>
                        </div>
                        
                        <!-- Nombre de personnes -->
                        <div class="mb-3">
                            <label for="nb_personnes" class="form-label">Nombre de personnes *</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="nb_personnes" 
                                   name="nb_personnes" 
                                   min="<?= $menu['nb_personnes_min'] ?>" 
                                   value="<?= $menu['nb_personnes_min'] ?>"
                                   required
                                   onchange="calculatePrice()">
                            <small class="text-muted">Minimum : <?= $menu['nb_personnes_min'] ?> personnes</small>
                        </div>
                        
                        <!-- Adresse de livraison -->
                        <div class="mb-3">
                            <label for="adresse_livraison" class="form-label">Adresse de livraison *</label>
                            <textarea class="form-control" 
                                      id="adresse_livraison" 
                                      name="adresse_livraison" 
                                      rows="3" 
                                      required><?= htmlspecialchars($user['adresse_postale'] ?? '') ?></textarea>
                            <small class="text-muted">Vous pouvez modifier l'adresse si necessaire</small>
                        </div>
                        
                        <!-- Date de livraison -->
                        <div class="mb-3">
                            <label for="date_livraison" class="form-label">Date de livraison *</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_livraison" 
                                   name="date_livraison" 
                                   min="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                                   required>
                            <small class="text-muted">Commande a passer au moins 7 jours avant la livraison</small>
                        </div>
                        
                        <!-- Heure de livraison -->
                        <div class="mb-3">
                            <label for="heure_livraison" class="form-label">Heure de livraison *</label>
                            <input type="time" 
                                   class="form-control" 
                                   id="heure_livraison" 
                                   name="heure_livraison" 
                                   min="08:00"
                                   max="20:00"
                                   required>
                            <small class="text-muted">Entre 8h00 et 20h00</small>
                        </div>
                        
                        <!-- Récapitulatif des prix -->
                        <div class="alert alert-light border">
                            <h6>Recapitulatif :</h6>
                            <div class="d-flex justify-content-between">
                                <span>Prix du menu :</span>
                                <strong id="prix_menu"><?= number_format($menu['prix_base'], 2) ?> €</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Frais de livraison :</span>
                                <strong>5.00 €</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5">Total :</span>
                                <strong class="h5 text-success" id="prix_total"><?= number_format($menu['prix_base'] + 5, 2) ?> €</strong>
                            </div>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                ✅ Valider la commande
                            </button>
                            <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Informations complémentaires -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>ℹ️ Informations</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Paiement a la livraison</li>
                        <li class="mb-2">📦 Livraison incluse (5€)</li>
                        <li class="mb-2">⏰ Delai minimum : 7 jours</li>
                        <li class="mb-2">🍽️ Materiel de service disponible sur demande</li>
                    </ul>
                </div>
            </div>
            
            <?php if ($menu['conditions']): ?>
                <div class="card mt-3">
                    <div class="card-body">
                        <h6>📋 Conditions :</h6>
                        <small><?= nl2br(htmlspecialchars($menu['conditions'])) ?></small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function calculatePrice() {
    const nbPersonnes = parseInt(document.getElementById('nb_personnes').value);
    const prixBase = <?= $menu['prix_base'] ?>;
    const nbMin = <?= $menu['nb_personnes_min'] ?>;
    const prixLivraison = 5.00;
    
    let prixMenu = prixBase;
    if (nbPersonnes > nbMin) {
        prixMenu = (prixBase / nbMin) * nbPersonnes;
    }
    
    const prixTotal = prixMenu + prixLivraison;
    
    document.getElementById('prix_menu').textContent = prixMenu.toFixed(2) + ' €';
    document.getElementById('prix_total').textContent = prixTotal.toFixed(2) + ' €';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>