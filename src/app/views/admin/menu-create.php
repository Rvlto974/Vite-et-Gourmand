<?php 
$page_title = 'Ajouter un menu';
ob_start(); 
?>

<div class="mb-3">
    <a href="/menu/adminList" class="btn btn-outline-secondary">← Retour a la liste</a>
</div>

<div class="card">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">➕ Ajouter un nouveau menu</h5>
    </div>
    <div class="card-body">
        <form action="/menu/adminStore" method="POST">
            <div class="row">
                <!-- Titre -->
                <div class="col-md-8 mb-3">
                    <label for="titre" class="form-label">Titre du menu *</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                
                <!-- Actif -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Statut</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="actif" name="actif" checked>
                        <label class="form-check-label" for="actif">Menu actif</label>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            
            <div class="row">
                <!-- Prix de base -->
                <div class="col-md-4 mb-3">
                    <label for="prix_base" class="form-label">Prix de base (€) *</label>
                    <input type="number" step="0.01" class="form-control" id="prix_base" name="prix_base" required>
                </div>
                
                <!-- Nombre de personnes min -->
                <div class="col-md-4 mb-3">
                    <label for="nb_personnes_min" class="form-label">Nb personnes min *</label>
                    <input type="number" class="form-control" id="nb_personnes_min" name="nb_personnes_min" value="1" required>
                </div>
                
                <!-- Stock -->
                <div class="col-md-4 mb-3">
                    <label for="stock_disponible" class="form-label">Stock disponible *</label>
                    <input type="number" class="form-control" id="stock_disponible" name="stock_disponible" value="0" required>
                </div>
            </div>
            
            <div class="row">
                <!-- Theme -->
                <div class="col-md-6 mb-3">
                    <label for="id_theme" class="form-label">Theme</label>
                    <select class="form-select" id="id_theme" name="id_theme">
                        <option value="">Aucun theme</option>
                        <?php foreach ($themes as $theme): ?>
                            <option value="<?= $theme['id_theme'] ?>">
                                <?= htmlspecialchars($theme['nom_theme']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Regime -->
                <div class="col-md-6 mb-3">
                    <label for="id_regime" class="form-label">Regime alimentaire</label>
                    <select class="form-select" id="id_regime" name="id_regime">
                        <option value="">Aucun regime</option>
                        <?php foreach ($regimes as $regime): ?>
                            <option value="<?= $regime['id_regime'] ?>">
                                <?= htmlspecialchars($regime['nom_regime']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <!-- Conditions -->
            <div class="mb-3">
                <label for="conditions" class="form-label">Conditions particulieres</label>
                <textarea class="form-control" id="conditions" name="conditions" rows="3"></textarea>
                <small class="text-muted">Ex: Commande a passer 7 jours a l'avance</small>
            </div>
            
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    ✅ Creer le menu
                </button>
                <a href="/menu/adminList" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>