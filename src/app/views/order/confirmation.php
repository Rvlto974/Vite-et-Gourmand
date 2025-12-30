<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <!-- Message de succès -->
            <div class="alert alert-success text-center py-5">
                <h1 class="display-4">✅ Commande validee !</h1>
                <p class="lead">Votre commande a ete enregistree avec succes</p>
                <hr>
                <p class="mb-0">Numero de commande : <strong>#<?= $order['id_commande'] ?></strong></p>
            </div>
            
            <!-- Détails de la commande -->
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📋 Recapitulatif de votre commande</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Menu -->
                    <div class="mb-4">
                        <h5>Menu commande :</h5>
                        <h3 class="text-primary"><?= htmlspecialchars($order['menu_titre']) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($order['description']) ?></p>
                    </div>
                    
                    <hr>
                    
                    <!-- Informations -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>👥 Nombre de personnes :</h6>
                            <p><?= $order['nb_personnes'] ?> personnes</p>
                        </div>
                        <div class="col-md-6">
                            <h6>📅 Date et heure de livraison :</h6>
                            <p><?= date('d/m/Y', strtotime($order['date_livraison'])) ?> a <?= date('H:i', strtotime($order['heure_livraison'])) ?></p>
                        </div>
                    </div>
                    
                    <!-- Adresse -->
                    <div class="mb-4">
                        <h6>📍 Adresse de livraison :</h6>
                        <p><?= nl2br(htmlspecialchars($order['adresse_livraison'])) ?></p>
                    </div>
                    
                    <hr>
                    
                    <!-- Prix -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Prix du menu :</span>
                            <strong><?= number_format($order['prix_menu'], 2) ?> €</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Frais de livraison :</span>
                            <strong><?= number_format($order['prix_livraison'], 2) ?> €</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5>Total :</h5>
                            <h5 class="text-success"><?= number_format($order['prix_total'], 2) ?> €</h5>
                        </div>
                    </div>
                    
                    <!-- Statut -->
                    <div class="alert alert-warning">
                        <strong>Statut :</strong> 
                        <span class="badge bg-warning">En attente de validation</span>
                    </div>
                    
                    <!-- Informations complémentaires -->
                    <div class="alert alert-info">
                        <h6>ℹ️ Que se passe-t-il maintenant ?</h6>
                        <ol class="mb-0">
                            <li>Notre equipe va valider votre commande sous 24h</li>
                            <li>Vous recevrez un email de confirmation</li>
                            <li>Nous preparerons votre menu pour la date demandee</li>
                            <li>Livraison a l'adresse indiquee</li>
                            <li>Paiement a la livraison</li>
                        </ol>
                    </div>
                    
                    <a href="/order/downloadPdf/<?= $order['id_commande'] ?>" class="btn btn-danger btn-lg">
                            📄 Télécharger la facture PDF
                        </a>

                    <!-- Boutons -->
                    <div class="d-grid gap-2">
                        <a href="/menu" class="btn btn-primary btn-lg">
                            Retour aux menus
                        </a>
                        <a href="/" class="btn btn-outline-secondary">
                            Retour a l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>