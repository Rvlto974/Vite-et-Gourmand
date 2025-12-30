<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">📅 Réserver ce menu</h3>
                </div>
                <div class="card-body">
                    
                    <!-- Infos menu -->
                    <div class="alert alert-info mb-4">
                        <h5 class="mb-2"><?= htmlspecialchars($menu['titre']) ?></h5>
                        <p class="mb-1"><?= htmlspecialchars($menu['description']) ?></p>
                        <strong>Prix : <?= number_format($menu['prix_base'], 2) ?> € / personne</strong>
                    </div>
                    
                    <!-- Formulaire -->
                    <form method="POST" action="/reservation/store" id="reservationForm">
                        <input type="hidden" name="menu_id" value="<?= $menu['id_menu'] ?>">
                        
                        <!-- Date -->
                        <div class="mb-3">
                            <label class="form-label">Date de réservation *</label>
                            <input type="date" 
                                   class="form-control" 
                                   name="date_reservation" 
                                   id="dateReservation"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   required>
                            <small class="text-muted">Réservation possible à partir de demain</small>
                        </div>
                        
                        <!-- Heure -->
                        <div class="mb-3">
                            <label class="form-label">Créneau horaire *</label>
                            <select class="form-select" name="heure_debut" id="heureDebut" required disabled>
                                <option value="">Sélectionnez d'abord une date</option>
                            </select>
                            <small class="text-muted">Créneaux de 2 heures disponibles</small>
                        </div>
                        
                        <!-- Nombre de personnes -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de personnes *</label>
                            <input type="number" 
                                   class="form-control" 
                                   name="nb_personnes" 
                                   id="nbPersonnes"
                                   min="<?= $menu['nb_personnes_min'] ?>" 
                                   value="<?= $menu['nb_personnes_min'] ?>"
                                   required>
                            <small class="text-muted">Minimum : <?= $menu['nb_personnes_min'] ?> personnes</small>
                        </div>
                        
                        <!-- Adresse de livraison -->
                        <div class="mb-3">
                            <label class="form-label">Adresse de livraison *</label>
                            <textarea class="form-control" 
                                      name="adresse_livraison" 
                                      rows="3" 
                                      required><?= htmlspecialchars($_SESSION['user_adresse'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Commentaire -->
                        <div class="mb-3">
                            <label class="form-label">Commentaire / Instructions spéciales</label>
                            <textarea class="form-control" 
                                      name="commentaire" 
                                      rows="3" 
                                      placeholder="Allergies, préférences, instructions particulières..."></textarea>
                        </div>
                        
                        <!-- Estimation prix -->
                        <div class="alert alert-success">
                            <h5 class="mb-2">💰 Estimation du prix</h5>
                            <p class="mb-0">
                                <span id="prixEstime"><?= number_format($menu['prix_base'] * $menu['nb_personnes_min'], 2) ?></span> €
                                <small class="text-muted">(<?= $menu['prix_base'] ?> € × <span id="nbPersonnesDisplay"><?= $menu['nb_personnes_min'] ?></span> personnes)</small>
                            </p>
                        </div>
                        
                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">
                                ✅ Confirmer la réservation
                            </button>
                            <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-outline-secondary btn-lg">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const prixBase = <?= $menu['prix_base'] ?>;

// Mettre à jour le prix estimé
document.getElementById('nbPersonnes').addEventListener('input', function() {
    const nb = this.value;
    const total = prixBase * nb;
    document.getElementById('prixEstime').textContent = total.toFixed(2);
    document.getElementById('nbPersonnesDisplay').textContent = nb;
});

// Charger les créneaux disponibles quand la date change
document.getElementById('dateReservation').addEventListener('change', function() {
    const date = this.value;
    const heureSelect = document.getElementById('heureDebut');
    
    if (!date) return;
    
    heureSelect.disabled = true;
    heureSelect.innerHTML = '<option value="">Chargement...</option>';
    
    fetch('/reservation/availableSlots?date=' + date)
        .then(response => response.json())
        .then(data => {
            heureSelect.innerHTML = '';
            
            if (data.slots.length === 0) {
                heureSelect.innerHTML = '<option value="">Aucun créneau disponible</option>';
                heureSelect.disabled = true;
            } else {
                heureSelect.innerHTML = '<option value="">Choisissez un créneau</option>';
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot.time;
                    option.textContent = slot.label;
                    heureSelect.appendChild(option);
                });
                heureSelect.disabled = false;
            }
        });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>