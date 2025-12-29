-- Base de données vite_gourmand
CREATE DATABASE IF NOT EXISTS vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

-- Table utilisateur
CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    gsm VARCHAR(20) NOT NULL,
    adresse_postale TEXT NOT NULL,
    role ENUM('utilisateur', 'employe', 'admin') DEFAULT 'utilisateur',
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table theme
CREATE TABLE IF NOT EXISTS theme (
    id_theme INT AUTO_INCREMENT PRIMARY KEY,
    nom_theme VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Table regime
CREATE TABLE IF NOT EXISTS regime (
    id_regime INT AUTO_INCREMENT PRIMARY KEY,
    nom_regime VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Table menu
CREATE TABLE IF NOT EXISTS menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    prix_base DECIMAL(10, 2) NOT NULL,
    nb_personnes_min INT NOT NULL DEFAULT 1,
    stock_disponible INT NOT NULL DEFAULT 0,
    id_theme INT,
    id_regime INT,
    conditions TEXT,
    actif TINYINT(1) DEFAULT 1,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_theme) REFERENCES theme(id_theme) ON DELETE SET NULL,
    FOREIGN KEY (id_regime) REFERENCES regime(id_regime) ON DELETE SET NULL
);

-- Table image_menu
CREATE TABLE IF NOT EXISTS image_menu (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    chemin_fichier VARCHAR(255) NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE
);

-- Table plat
CREATE TABLE IF NOT EXISTS plat (
    id_plat INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    nom_plat VARCHAR(255) NOT NULL,
    description TEXT,
    type_plat ENUM('entree', 'plat', 'dessert') NOT NULL,
    ordre_affichage INT DEFAULT 0,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE
);

-- Table commande
CREATE TABLE IF NOT EXISTS commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_menu INT NOT NULL,
    nb_personnes INT NOT NULL,
    prix_menu DECIMAL(10, 2) NOT NULL,
    prix_livraison DECIMAL(10, 2) DEFAULT 0,
    prix_total DECIMAL(10, 2) NOT NULL,
    adresse_livraison TEXT NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    statut ENUM('en_attente', 'accepte', 'en_preparation', 'terminee', 'annulee') DEFAULT 'en_attente',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE
);

-- Table avis
CREATE TABLE IF NOT EXISTS avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_utilisateur INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    valide TINYINT(1) DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
);

-- Table contact
CREATE TABLE IF NOT EXISTS contact (
    id_contact INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    titre VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traite TINYINT(1) DEFAULT 0
);

-- INSERT données de test
INSERT INTO theme (nom_theme, description) VALUES 
('Noel', 'Menus festifs pour les fetes de fin d annee'),
('Paques', 'Menus de printemps'),
('Evenement', 'Menus pour evenements speciaux');

INSERT INTO regime (nom_regime, description) VALUES 
('Classique', 'Menu traditionnel'),
('Vegetarien', 'Sans viande ni poisson'),
('Vegan', 'Sans produits d origine animale');

INSERT INTO menu (titre, description, prix_base, nb_personnes_min, stock_disponible, id_theme, id_regime, actif) VALUES
('Menu Festif de Noel', 'Un repas traditionnel pour celebrer Noel en famille', 180.00, 6, 20, 1, 1, 1),
('Menu de Paques', 'Un menu festif pour celebrer Paques en famille avec des saveurs printanieres', 160.00, 6, 15, 2, 1, 1),
('Menu Vegetarien Raffine', 'Des saveurs vegetales pour un repas equilibre et gourmand', 120.00, 4, 25, 3, 2, 1);

-- Utilisateur test
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role) 
VALUES ('Test', 'User', 'test@test.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0600000000', '123 rue Test', 'admin');