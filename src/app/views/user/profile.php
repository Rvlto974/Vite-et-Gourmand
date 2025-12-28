<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">👤 Mon espace</h5>
                    <div class="list-group list-group-flush">
                        <a href="/user/dashboard" class="list-group-item list-group-item-action">
                            📊 Tableau de bord
                        </a>
                        <a href="/user/orders" class="list-group-item list-group-item-action">
                            📦 Mes commandes
                        </a>
                        <a href="/user/profile" class="list-group-item list-group-item-action active">
                            ⚙️ Mon profil
                        </a>
                        <a href="/auth/logout" class="list-group-item list-group-item-action text-danger">
                            🚪 Deconnexion
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-9">
            <h1 class="mb-4">⚙️ Mon profil</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Informations du compte -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">📋 Informations du compte</h5>
                </div>
                <div class="card-body">
                    <form action="/user/updateProfile" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?= htmlspecialchars($user['nom']) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">Prenom *</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                       value="<?= htmlspecialchars($user['prenom']) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <small class="text-muted">L'email ne peut pas etre modifie</small>
                        </div>

                        <div class="mb-3">
                            <label for="gsm" class="form-label">Telephone *</label>
                            <input type="tel" class="form-control" id="gsm" name="gsm" 
                                   value="<?= htmlspecialchars($user['gsm']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse postale *</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="3" required><?= htmlspecialchars($user['adresse_postale']) ?></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                💾 Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations du compte (non modifiables) -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">ℹ️ Informations du compte</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Role :</strong><br>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'employe' ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Statut :</strong><br>
                            <span class="badge bg-<?= $user['actif'] ? 'success' : 'danger' ?>">
                                <?= $user['actif'] ? 'Actif' : 'Inactif' ?>
                            </span>
                        </div>
                        <div class="col-md-12">
                            <strong>Membre depuis :</strong><br>
                            <?= date('d/m/Y', strtotime($user['date_creation'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>