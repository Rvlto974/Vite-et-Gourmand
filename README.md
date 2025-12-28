# 🍽️ Vite & Gourmand - Application de Traiteur Événementiel

Application web complète pour la gestion des commandes et menus d'un service de traiteur événementiel.

## 📋 Table des matières

- [Présentation](#présentation)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Structure du projet](#structure-du-projet)
- [Technologies utilisées](#technologies-utilisées)
- [Comptes de test](#comptes-de-test)
- [Documentation](#documentation)

---

## 🎯 Présentation

Vite & Gourmand est une application web permettant de :
- Consulter et commander des menus pour événements
- Gérer les utilisateurs (visiteurs, clients, employés, administrateurs)
- Suivre les commandes et leur statut
- Gérer les avis clients
- Visualiser des statistiques (administrateur)

### Fonctionnalités principales

✅ Partie publique :
- Page d'accueil avec présentation
- Catalogue de menus avec filtres dynamiques
- Création de compte et connexion
- Commande de menus en ligne
- Suivi de commandes
- Dépôt d'avis

✅ Espace employé :
- Gestion des menus et plats
- Gestion des commandes
- Validation des avis

✅ Espace administrateur :
- Toutes les fonctionnalités employé
- Création de comptes employés
- Statistiques et analytics (MongoDB)
- Tableau de bord

---

## 🛠️ Prérequis

### Logiciels requis

- [Docker Desktop](https://www.docker.com/products/docker-desktop) (Windows/Mac/Linux)
- [Git](https://git-scm.com/downloads)
- [Visual Studio Code](https://code.visualstudio.com/) (recommandé)

### Extensions VS Code recommandées

- PHP Intelephense
- Docker
- GitLens
- MySQL (cweijan)

---

## 🚀 Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-username/vite-et-gourmand.git
cd vite-et-gourmand
```

### 2. Lancer Docker

```bash
# Construire et démarrer tous les conteneurs
docker-compose up -d

# Vérifier que tous les conteneurs sont lancés
docker-compose ps
```

### 3. Vérifier l'installation

Ouvrez votre navigateur et accédez à :
- **Application** : http://localhost:8080
- **phpMyAdmin** : http://localhost:8081
- **Mongo Express** : http://localhost:8082

Si la page de test s'affiche avec tous les voyants verts ✅, l'installation est réussie !

### 4. Arrêter les conteneurs

```bash
# Arrêter les conteneurs
docker-compose stop

# Arrêter et supprimer les conteneurs
docker-compose down

# Arrêter et supprimer conteneurs + volumes (⚠️ supprime les données)
docker-compose down -v
```

---

## 📖 Utilisation

### Accès aux services

| Service | URL | Credentials |
|---------|-----|-------------|
| **Application** | http://localhost:8080 | Voir comptes de test |
| **phpMyAdmin** | http://localhost:8081 | User: `root` / Pass: `root_password` |
| **Mongo Express** | http://localhost:8082 | User: `admin` / Pass: `admin` |

### Commandes Docker utiles

```bash
# Voir les logs en temps réel
docker-compose logs -f

# Voir les logs d'un service spécifique
docker-compose logs -f web

# Redémarrer un service
docker-compose restart web

# Accéder au terminal d'un conteneur
docker-compose exec web bash

# Reconstruire les images
docker-compose build

# Reconstruire et redémarrer
docker-compose up -d --build
```

### Réinitialiser la base de données

```bash
# Stopper les conteneurs
docker-compose down

# Supprimer les volumes (⚠️ supprime toutes les données)
docker volume rm vite-et-gourmand_mysql_data
docker volume rm vite-et-gourmand_mongo_data

# Redémarrer (la BDD sera recréée)
docker-compose up -d
```

---

## 📁 Structure du projet

```
vite-et-gourmand/
├── config/                  # Configuration PHP
│   └── php.ini
├── database/               # Scripts SQL
│   └── init.sql           # Création BDD + données de test
├── docs/                   # Documentation
│   ├── charte-graphique.pdf
│   ├── manuel-utilisateur.pdf
│   ├── documentation-technique.pdf
│   └── gestion-projet.pdf
├── src/                    # Code source
│   ├── public/            # Front-end (accessible web)
│   │   ├── index.php
│   │   ├── .htaccess
│   │   ├── css/
│   │   ├── js/
│   │   └── uploads/
│   ├── app/               # Back-end
│   │   ├── controllers/
│   │   ├── models/
│   │   ├── views/
│   │   └── config/
│   └── vendor/            # Dépendances Composer
├── wireframes/            # Maquettes du projet
│   ├── desktop/
│   └── mobile/
├── .gitignore
├── docker-compose.yml     # Configuration Docker
├── Dockerfile            # Image PHP personnalisée
└── README.md
```

---

## 🔧 Technologies utilisées

### Front-end
- **HTML5** - Structure sémantique
- **CSS3** + **Bootstrap 5** - Styles et responsive design
- **JavaScript** (vanilla) - Interactions dynamiques

### Back-end
- **PHP 8.2** - Langage serveur
- **PDO** - Connexion sécurisée MySQL
- **MongoDB Driver** - Connexion MongoDB

### Bases de données
- **MySQL 8.0** - Base relationnelle (menus, commandes, utilisateurs)
- **MongoDB 7.0** - Base NoSQL (statistiques)

### Outils
- **Docker** - Conteneurisation
- **Composer** - Gestionnaire de dépendances PHP
- **Git** - Gestion de version

---

## 👤 Comptes de test

### Administrateur (José)
```
Email : jose@vitegourmand.fr
Mot de passe : Admin123!
```

### Employé (Julie)
```
Email : julie@vitegourmand.fr
Mot de passe : Employe123!
```

### Utilisateurs
```
Email : sophie.martin@email.fr
Mot de passe : User123!

Email : pierre.dupont@email.fr
Mot de passe : User123!
```

> ⚠️ **Note** : Ces mots de passe sont à titre de développement uniquement. En production, utilisez des mots de passe forts et uniques.

---

## 📚 Documentation

La documentation complète du projet est disponible dans le dossier `/docs` :

- **Charte graphique** - Palette de couleurs, polices, wireframes, mockups
- **Manuel utilisateur** - Guide d'utilisation de l'application
- **Documentation technique** - Architecture, MCD, diagrammes UML
- **Gestion de projet** - Méthodologie, planification, suivi

---

## 🔐 Sécurité

### Mesures implémentées

✅ Hashage des mots de passe (bcrypt)  
✅ Protection contre les injections SQL (PDO avec requêtes préparées)  
✅ Protection XSS (échappement HTML)  
✅ Protection CSRF (tokens)  
✅ Sessions sécurisées (httponly, secure, samesite)  
✅ Headers de sécurité (X-Frame-Options, CSP, etc.)  
✅ Conformité RGPD  
✅ Conformité RGAA (accessibilité)

---

## 🌐 Déploiement

### Plateformes supportées

L'application peut être déployée sur :
- [Fly.io](https://fly.io)
- [Heroku](https://www.heroku.com)
- [Azure](https://azure.microsoft.com)
- [Vercel](https://vercel.com) (avec adaptations)

### Prérequis pour le déploiement

1. Créer les bases de données MySQL et MongoDB sur le cloud
2. Configurer les variables d'environnement
3. Activer HTTPS (obligatoire)
4. Configurer les DNS si domaine personnalisé

> 📖 Voir la documentation technique pour les instructions détaillées de déploiement.

---

## 🤝 Contribution

Ce projet est développé dans le cadre d'une évaluation en cours de formation pour le titre professionnel "Développeur Web et Web Mobile".

### Développeurs

- **Votre Nom** - Développeur full-stack

### Entreprise cliente

- **Vite & Gourmand** - Julie & José
- Traiteur événementiel à Bordeaux depuis 25 ans

### Agence de développement

- **FastDev** - Développement web professionnel

---

## 📄 Licence

Ce projet est développé à des fins éducatives dans le cadre d'une formation professionnelle.

---

## 📞 Support

Pour toute question ou assistance :
- **Issues GitHub** : [Créer une issue](https://github.com/votre-username/vite-et-gourmand/issues)
- **Email** : votre.email@example.com

---

## 🎓 Contexte du projet

Projet réalisé dans le cadre de l'ECF (Évaluation en Cours de Formation) pour le titre professionnel **Développeur Web et Web Mobile** organisé par Studi.

**Durée indicative** : 70 heures  
**Compétences évaluées** : 
- Développement front-end (maquettage, intégration, interfaces dynamiques)
- Développement back-end (BDD, accès aux données, composants métier)
- Déploiement et documentation

---

<div align="center">
  <p>Développé avec ❤️ par FastDev</p>
  <p>Pour Vite & Gourmand - Bordeaux</p>
</div>
