-- Base de données Vite & Gourmand

DROP DATABASE IF EXISTS vite_gourmand;
CREATE DATABASE vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

-- Table utilisateur
CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    gsm VARCHAR(20) NOT NULL,
    adresse_postale TEXT NOT NULL,
    role ENUM('utilisateur', 'employe', 'admin') DEFAULT 'utilisateur',
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table theme
CREATE TABLE theme (
    id_theme INT AUTO_INCREMENT PRIMARY KEY,
    nom_theme VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table regime
CREATE TABLE regime (
    id_regime INT AUTO_INCREMENT PRIMARY KEY,
    nom_regime VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table menu
CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    id_theme INT,
    id_regime INT,
    nb_personnes_min INT NOT NULL,
    prix_base DECIMAL(10,2) NOT NULL,
    stock_disponible INT DEFAULT 0,
    conditions TEXT,
    actif BOOLEAN DEFAULT TRUE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_theme) REFERENCES theme(id_theme) ON DELETE SET NULL,
    FOREIGN KEY (id_regime) REFERENCES regime(id_regime) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table allergene
CREATE TABLE allergene (
    id_allergene INT AUTO_INCREMENT PRIMARY KEY,
    nom_allergene VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table plat
CREATE TABLE plat (
    id_plat INT AUTO_INCREMENT PRIMARY KEY,
    nom_plat VARCHAR(200) NOT NULL,
    type_plat ENUM('entree', 'plat', 'dessert') NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table commande
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_menu INT NOT NULL,
    nb_personnes INT NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    adresse_livraison TEXT NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    statut ENUM('en_attente', 'accepte', 'en_preparation', 'en_livraison', 'livre', 'terminee', 'annulee') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table avis
CREATE TABLE avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_utilisateur INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    valide BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de test
INSERT INTO theme (nom_theme) VALUES ('Noël'), ('Pâques'), ('Classique'), ('Événement');
INSERT INTO regime (nom_regime) VALUES ('Classique'), ('Végétarien'), ('Vegan');
INSERT INTO allergene (nom_allergene) VALUES ('Gluten'), ('Lactose'), ('Fruits à coque'), ('Œufs');

-- Compte admin (mot de passe: Admin123!)
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role) VALUES 
('García', 'José', 'jose@vitegourmand.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0612345678', '123 Rue du Traiteur, 33000 Bordeaux', 'admin');

-- Menu test
INSERT INTO menu (titre, description, id_theme, id_regime, nb_personnes_min, prix_base, stock_disponible, conditions) VALUES 
('Menu Festif de Noël', 'Un repas traditionnel pour Noël', 1, 1, 6, 180.00, 10, 'Commande 7 jours avant');

SELECT 'Base de données créée avec succès !' AS message;