<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">📧 Contactez-nous</h3>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Informations de contact -->
                    <div class="alert alert-info mb-4">
                        <h5>📍 Nos coordonnées</h5>
                        <p class="mb-2"><strong>📞 Téléphone :</strong> 06 12 34 56 78</p>
                        <p class="mb-2"><strong>📧 Email :</strong> contact@vitegourmand.fr</p>
                        <p class="mb-0"><strong>📍 Adresse :</strong> 123 Rue de la Gastronomie, 33000 Bordeaux</p>
                    </div>
                    
                    <h5 class="mb-3">Envoyez-nous un message</h5>
                    
                    <form action="/contact/store" method="POST">
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Votre email *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   placeholder="votre.email@exemple.com">
                        </div>
                        
                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="titre" class="form-label">Sujet *</label>
                            <input type="text" class="form-control" id="titre" name="titre" required
                                   placeholder="Ex: Question sur une commande">
                        </div>
                        
                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required
                                      placeholder="Ecrivez votre message ici..."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                📨 Envoyer le message
                            </button>
                            <a href="/" class="btn btn-outline-secondary">
                                ← Retour à l'accueil
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Section FAQ -->
            <div class="card shadow mt-4">
                <div class="card-header">
                    <h5>❓ Questions fréquentes</h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Quel est le délai de commande ?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Les commandes doivent être passées au minimum 7 jours avant la date de livraison souhaitée.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Quels sont les modes de paiement ?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Le paiement s'effectue à la livraison par chèque, espèces ou carte bancaire.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Livrez-vous le matériel de service ?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, nous fournissons gratuitement le matériel de service (assiettes, couverts, verres) avec chaque commande.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>