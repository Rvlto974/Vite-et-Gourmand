<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-4">
    <div class="mb-3">
        <a href="/promo/adminList" class="btn btn-outline-secondary">← Retour à la liste</a>
    </div>

    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0">🎁 Créer un code promo</h3>
        </div>
        <div class="card-body">
            <form action="/promo/adminStore" method="POST">
                <div class="row">
                    <!-- Code -->
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">Code promo *</label>
                        <input type="text" 
                               class="form-control" 
                               id="code" 
                               name="code" 
                               placeholder="Ex: NOEL2025"
                               style="text-transform: uppercase;"
                               required>
                        <small class="text-muted">Le code sera automatiquement mis en majuscules</small>
                    </div>

                    <!-- Type -->
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Type de réduction *</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="pourcentage">Pourcentage (%)</option>
                            <option value="montant">Montant fixe (€)</option>
                        </select>
                    </div>

                    <!-- Valeur -->
                    <div class="col-md-6 mb-3">
                        <label for="valeur" class="form-label">Valeur de la réduction *</label>
                        <input type="number" 
                               class="form-control" 
                               id="valeur" 
                               name="valeur" 
                               step="0.01"
                               min="0"
                               placeholder="Ex: 10"
                               required>
                        <small class="text-muted">10 pour 10% ou 10€ selon le type</small>
                    </div>

                    <!-- Montant minimum -->
                    <div class="col-md-6 mb-3">
                        <label for="montant_minimum" class="form-label">Montant minimum de commande</label>
                        <input type="number" 
                               class="form-control" 
                               id="montant_minimum" 
                               name="montant_minimum" 
                               step="0.01"
                               value="0"
                               min="0">
                        <small class="text-muted">0 = pas de minimum</small>
                    </div>

                    <!-- Date début -->
                    <div class="col-md-6 mb-3">
                        <label for="date_debut" class="form-label">Date de début *</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_debut" 
                               name="date_debut"
                               value="<?= date('Y-m-d') ?>"
                               required>
                    </div>

                    <!-- Date fin -->
                    <div class="col-md-6 mb-3">
                        <label for="date_fin" class="form-label">Date de fin *</label>
                        <input type="date" 
                               class="form-control" 
                               id="date_fin" 
                               name="date_fin"
                               required>
                    </div>

                    <!-- Utilisation max -->
                    <div class="col-md-6 mb-3">
                        <label for="utilisation_max" class="form-label">Nombre d'utilisations maximum</label>
                        <input type="number" 
                               class="form-control" 
                               id="utilisation_max" 
                               name="utilisation_max" 
                               min="1"
                               placeholder="Laisser vide pour illimité">
                        <small class="text-muted">Laisser vide pour un nombre illimité</small>
                    </div>

                    <!-- Actif -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label d-block">Statut</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="actif" 
                                   name="actif"
                                   checked>
                            <label class="form-check-label" for="actif">
                                Code actif
                            </label>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="/promo/adminList" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">
                        ✅ Créer le code promo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>