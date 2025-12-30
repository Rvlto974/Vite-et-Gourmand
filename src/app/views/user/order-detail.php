<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="mb-3">
    <a href="/admin/orders" class="btn btn-outline-secondary">← Retour aux commandes</a>
</div>

<div class="row">
    <!-- Informations commande -->
    <div class="col-md-8">
        <!-- Statut -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📋 Commande #<?= $order['id_commande'] ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Date de commande :</strong><br>
                        <?= date('d/m/Y à H:i', strtotime($order['date_creation'])) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Statut actuel :</strong><br>
                        <?php
                        $badge_colors = [
                            'en_attente' => 'warning',
                            'accepte' => 'info',
                            'en_preparation' => 'primary',
                            'en_livraison' => 'primary',
                            'livre' => 'success',
                            'terminee' => 'success',
                            'annulee' => 'danger'
                        ];
                        $color = $badge_colors[$order['statut']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $color ?> fs-6">
                            <?= ucfirst(str_replace('_', ' ', $order['statut'])) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations client -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">👤 Informations client</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Nom :</strong><br>
                        <?= htmlspecialchars($order['prenom'] . ' ' . $order['nom']) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Email :</strong><br>
                        <a href="mailto:<?= htmlspecialchars($order['email']) ?>">
                            <?= htmlspecialchars($order['email']) ?>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <strong>Telephone :</strong><br>
                        <a href="tel:<?= htmlspecialchars($order['gsm']) ?>">
                            <?= htmlspecialchars($order['gsm']) ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails du menu -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">🍽️ Détails du menu</h5>
            </div>
            <div class="card-body">
                <h5><?= htmlspecialchars($order['menu_titre']) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($order['description']) ?></p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nombre de personnes :</strong> <?= $order['nb_personnes'] ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Prix de base :</strong> <?= number_format($order['prix_base'], 2) ?> €
                    </div>
                </div>
            </div>
        </div>

        <!-- Livraison -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">📍 Informations de livraison</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Date de livraison :</strong><br>
                        <?= date('d/m/Y', strtotime($order['date_livraison'])) ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Heure de livraison :</strong><br>
                        <?= date('H:i', strtotime($order['heure_livraison'])) ?>
                    </div>
                    <div class="col-md-12">
                        <strong>Adresse de livraison :</strong><br>
                        <?= nl2br(htmlspecialchars($order['adresse_livraison'])) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Récapitulatif prix -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">💰 Récapitulatif financier</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Prix du menu :</span>
                    <strong><?= number_format($order['prix_menu'], 2) ?> €</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Frais de livraison :</span>
                    <strong><?= number_format($order['prix_livraison'], 2) ?> €</strong>
                </div>
                <?php if (isset($order['montant_reduction']) && $order['montant_reduction'] > 0): ?>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Réduction code promo :</span>
                        <strong>- <?= number_format($order['montant_reduction'], 2) ?> €</strong>
                    </div>
                <?php endif; ?>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5>Total :</h5>
                    <h5 class="text-success"><?= number_format($order['prix_total'], 2) ?> €</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions admin -->
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-warning">
                <h5 class="mb-0">⚙️ Actions Admin</h5>
            </div>
            <div class="card-body">
                <form action="/admin/updateOrderStatus" method="POST">
                    <input type="hidden" name="order_id" value="<?= $order['id_commande'] ?>">

                    <div class="mb-3">
                        <label for="statut" class="form-label">Changer le statut :</label>
                        <select class="form-select" id="statut" name="statut" required>
                            <option value="en_attente" <?= $order['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                            <option value="accepte" <?= $order['statut'] === 'accepte' ? 'selected' : '' ?>>Acceptée</option>
                            <option value="en_preparation" <?= $order['statut'] === 'en_preparation' ? 'selected' : '' ?>>En préparation</option>
                            <option value="terminee" <?= $order['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                            <option value="annulee" <?= $order['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        <i class="bi bi-check-circle"></i> Mettre à jour le statut
                    </button>
                </form>

                <a href="/order/downloadPdf/<?= $order['id_commande'] ?>" class="btn btn-danger w-100 mb-2">
                    📄 Télécharger la facture PDF
                </a>

                <hr>

                <a href="/admin/orders" class="btn btn-outline-secondary w-100">
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>