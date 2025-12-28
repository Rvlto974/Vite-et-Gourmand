<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Accueil</a></li>
            <li class="breadcrumb-item"><a href="/menu">Menus</a></li>
            <li class="breadcrumb-item"><a href="/menu/show/<?= $menu['id_menu'] ?>"><?= htmlspecialchars($menu['titre']) ?></a></li>
            <li class="breadcrumb-item active">Laisser un avis</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">⭐ Laisser un avis</h3>
                </div>
                <div class="card-body">
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Menu concerné -->
                    <div class="alert alert-info">
                        <h5>Menu : <?= htmlspecialchars($menu['titre']) ?></h5>
                        <p class="mb-0"><?= htmlspecialchars($menu['description']) ?></p>
                    </div>
                    
                    <form action="/avis/store" method="POST">
                        <input type="hidden" name="menu_id" value="<?= $menu['id_menu'] ?>">
                        
                        <!-- Note -->
                        <div class="mb-4">
                            <label class="form-label">Votre note *</label>
                            <div class="rating-stars" id="rating-container">
                                <span class="star" data-value="1">☆</span>
                                <span class="star" data-value="2">☆</span>
                                <span class="star" data-value="3">☆</span>
                                <span class="star" data-value="4">☆</span>
                                <span class="star" data-value="5">☆</span>
                            </div>
                            <input type="hidden" name="note" id="note" required>
                            <div class="form-text">Cliquez sur les etoiles pour noter</div>
                        </div>
                        
                        <!-- Commentaire -->
                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Votre commentaire *</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" rows="5" required 
                                        placeholder="Partagez votre experience avec ce menu..."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                ✅ Envoyer mon avis
                            </button>
                            <a href="/menu/show/<?= $menu['id_menu'] ?>" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-stars {
    font-size: 3rem;
    cursor: pointer;
    user-select: none;
}
.star {
    color: #ddd;
    transition: color 0.2s;
}
.star:hover,
.star.active {
    color: #ffc107;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const noteInput = document.getElementById('note');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            noteInput.value = value;
            
            // Mettre à jour l'affichage des étoiles
            stars.forEach((s, index) => {
                if (index < value) {
                    s.textContent = '★';
                    s.classList.add('active');
                } else {
                    s.textContent = '☆';
                    s.classList.remove('active');
                }
            });
        });
        
        // Effet hover
        star.addEventListener('mouseenter', function() {
            const value = this.getAttribute('data-value');
            stars.forEach((s, index) => {
                if (index < value) {
                    s.textContent = '★';
                } else {
                    s.textContent = '☆';
                }
            });
        });
    });
    
    // Restaurer les étoiles au survol
    document.getElementById('rating-container').addEventListener('mouseleave', function() {
        const currentValue = noteInput.value;
        if (currentValue) {
            stars.forEach((s, index) => {
                if (index < currentValue) {
                    s.textContent = '★';
                } else {
                    s.textContent = '☆';
                }
            });
        } else {
            stars.forEach(s => s.textContent = '☆');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>