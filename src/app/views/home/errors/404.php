<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container text-center py-5">
    <div class="error-404" style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        
        <div class="mb-4" style="animation: bounce 2s infinite;">
            <h1 class="display-1 fw-bold" style="font-size: 10rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                404
            </h1>
        </div>
        
        <h2 class="display-5 fw-bold mb-3">Oups ! Page introuvable</h2>
        <p class="lead text-muted mb-4">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
        
        <div class="mb-5" style="font-size: 5rem;">🍽️🔍❓</div>
        
        <div class="row g-3 justify-content-center mb-4" style="max-width: 600px;">
            <div class="col-md-4">
                <a href="/" class="btn btn-primary btn-lg w-100">🏠 Accueil</a>
            </div>
            <div class="col-md-4">
                <a href="/menu" class="btn btn-outline-primary btn-lg w-100">🍽️ Nos menus</a>
            </div>
            <div class="col-md-4">
                <a href="/contact" class="btn btn-outline-secondary btn-lg w-100">📧 Contact</a>
            </div>
        </div>
        
        <div class="alert alert-info" style="max-width: 500px;">
            <strong>💡 Le saviez-vous ?</strong> Même nos chefs se perdent parfois en cuisine !
        </div>
    </div>
</div>

<style>
@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
.error-404 a { transition: all 0.3s ease; }
.error-404 a:hover { transform: scale(1.05); }
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>