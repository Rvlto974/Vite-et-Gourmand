<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vite & Gourmand - Traiteur Événementiel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Toast JS -->
    <script src="/js/toast.js"></script>
    <script>
        <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
        var toastMessages = [];
        <?php if (isset($_SESSION['success'])): ?>
        toastMessages.push({ message: '<?= addslashes($_SESSION['success']) ?>', type: 'success' });
        <?php unset($_SESSION['success']); endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
        toastMessages.push({ message: '<?= addslashes($_SESSION['error']) ?>', type: 'error' });
        <?php unset($_SESSION['error']); endif; ?>
        <?php endif; ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">🍽️ Vite & Gourmand</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <!-- Sélecteur de langue -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <?php if (Language::getCurrentLanguage() === 'fr'): ?>
                                🇫🇷 FR
                            <?php else: ?>
                                🇬🇧 EN
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/language/switch/fr">🇫🇷 Français</a></li>
                            <li><a class="dropdown-item" href="/language/switch/en">🇬🇧 English</a></li>
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="/">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="/menu">Menus</a></li>
                    <li class="nav-item"><a class="nav-link" href="/home/about">A propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Menu utilisateur connecte -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                👤 <?= htmlspecialchars($_SESSION['user_prenom']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                    <li><a class="dropdown-item text-warning fw-bold" href="/admin">
                                        🔧 Administration
                                    </a></li>
                                    <li><a class="dropdown-item" href="/analytics/adminDashboard">📊 Analytics</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="/user/dashboard">Mon espace</a></li>
                                <li><a class="dropdown-item" href="/favori/index">⭐ Mes favoris</a></li>
                                <li><a class="dropdown-item" href="/user/orders">Mes commandes</a></li>
                                <li><a class="dropdown-item" href="/user/dashboard">Mon espace</a></li>
                                <li><a class="dropdown-item" href="/user/orders">Mes commandes</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/auth/logout">Deconnexion</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Menu non connecte -->
                        <li class="nav-item"><a class="nav-link" href="/auth/login">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link btn btn-success text-white ms-2" href="/auth/register">S'inscrire</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>