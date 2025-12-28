-- =====================================================
-- Base de données : vite_gourmand
-- Application de traiteur événementiel
-- =====================================================

DROP DATABASE IF EXISTS vite_gourmand;
CREATE DATABASE vite_gourmand CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vite_gourmand;

-- =====================================================
-- TABLE : utilisateur
-- =====================================================
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

-- =====================================================
-- TABLE : theme
-- =====================================================
CREATE TABLE theme (
    id_theme INT AUTO_INCREMENT PRIMARY KEY,
    nom_theme VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : regime
-- =====================================================
CREATE TABLE regime (
    id_regime INT AUTO_INCREMENT PRIMARY KEY,
    nom_regime VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : menu
-- =====================================================
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
    FOREIGN KEY (id_regime) REFERENCES regime(id_regime) ON DELETE SET NULL,
    INDEX idx_theme (id_theme),
    INDEX idx_regime (id_regime),
    INDEX idx_actif (actif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : image
-- =====================================================
CREATE TABLE image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_menu INT NOT NULL,
    url_image VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    ordre_affichage INT DEFAULT 0,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE,
    INDEX idx_menu (id_menu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : allergene
-- =====================================================
CREATE TABLE allergene (
    id_allergene INT AUTO_INCREMENT PRIMARY KEY,
    nom_allergene VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : plat
-- =====================================================
CREATE TABLE plat (
    id_plat INT AUTO_INCREMENT PRIMARY KEY,
    nom_plat VARCHAR(200) NOT NULL,
    type_plat ENUM('entree', 'plat', 'dessert') NOT NULL,
    description TEXT,
    INDEX idx_type (type_plat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : plat_allergene (relation N-N)
-- =====================================================
CREATE TABLE plat_allergene (
    id_plat INT NOT NULL,
    id_allergene INT NOT NULL,
    PRIMARY KEY (id_plat, id_allergene),
    FOREIGN KEY (id_plat) REFERENCES plat(id_plat) ON DELETE CASCADE,
    FOREIGN KEY (id_allergene) REFERENCES allergene(id_allergene) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : menu_plat (relation N-N)
-- =====================================================
CREATE TABLE menu_plat (
    id_menu INT NOT NULL,
    id_plat INT NOT NULL,
    PRIMARY KEY (id_menu, id_plat),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE CASCADE,
    FOREIGN KEY (id_plat) REFERENCES plat(id_plat) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : commande
-- =====================================================
CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    id_menu INT NOT NULL,
    nb_personnes INT NOT NULL,
    prix_menu DECIMAL(10,2) NOT NULL,
    prix_livraison DECIMAL(10,2) DEFAULT 0,
    prix_total DECIMAL(10,2) NOT NULL,
    adresse_livraison TEXT NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    statut ENUM('en_attente', 'accepte', 'en_preparation', 'en_livraison', 'livre', 'en_attente_materiel', 'terminee', 'annulee') DEFAULT 'en_attente',
    materiel_prete BOOLEAN DEFAULT FALSE,
    motif_annulation TEXT,
    mode_contact_annulation VARCHAR(50),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu) ON DELETE RESTRICT,
    INDEX idx_utilisateur (id_utilisateur),
    INDEX idx_statut (statut),
    INDEX idx_date_livraison (date_livraison)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : historique_statut
-- =====================================================
CREATE TABLE historique_statut (
    id_historique INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    ancien_statut VARCHAR(50),
    nouveau_statut VARCHAR(50) NOT NULL,
    date_changement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    INDEX idx_commande (id_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : avis
-- =====================================================
CREATE TABLE avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_commande INT NOT NULL,
    id_utilisateur INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    valide BOOLEAN DEFAULT FALSE,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    INDEX idx_valide (valide),
    INDEX idx_commande (id_commande)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : horaires
-- =====================================================
CREATE TABLE horaires (
    id_horaire INT AUTO_INCREMENT PRIMARY KEY,
    jour_semaine ENUM('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche') NOT NULL UNIQUE,
    heure_ouverture TIME,
    heure_fermeture TIME,
    ferme BOOLEAN DEFAULT FALSE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE : contact
-- =====================================================
CREATE TABLE contact (
    id_contact INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    titre VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traite BOOLEAN DEFAULT FALSE,
    INDEX idx_traite (traite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DONNÉES DE TEST
-- =====================================================

-- Insertion des thèmes
INSERT INTO theme (nom_theme) VALUES 
('Noël'), ('Pâques'), ('Classique'), ('Événement');

-- Insertion des régimes
INSERT INTO regime (nom_regime) VALUES 
('Classique'), ('Végétarien'), ('Vegan'), ('Sans gluten');

-- Insertion des allergènes
INSERT INTO allergene (nom_allergene) VALUES 
('Gluten'), ('Lactose'), ('Fruits à coque'), ('Œufs'), ('Poisson'), 
('Crustacés'), ('Soja'), ('Céleri'), ('Moutarde'), ('Sésame');

-- Insertion des horaires
INSERT INTO horaires (jour_semaine, heure_ouverture, heure_fermeture, ferme) VALUES 
('lundi', '09:00:00', '18:00:00', FALSE),
('mardi', '09:00:00', '18:00:00', FALSE),
('mercredi', '09:00:00', '18:00:00', FALSE),
('jeudi', '09:00:00', '18:00:00', FALSE),
('vendredi', '09:00:00', '18:00:00', FALSE),
('samedi', '10:00:00', '16:00:00', FALSE),
('dimanche', NULL, NULL, TRUE);

-- Compte admin (José) - Mot de passe : Admin123!

INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role, actif) VALUES 
('Garcia', 'Jose', 'jose@vitegourmand.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0612345678', '123 Rue du Traiteur, 33000 Bordeaux', 'admin', TRUE);

-- Compte employé (Julie) - Mot de passe : Employe123!
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role, actif) VALUES 
('Dubois', 'Julie', 'julie@vitegourmand.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0623456789', '123 Rue du Traiteur, 33000 Bordeaux', 'employe', TRUE);

-- Utilisateurs de test - Mot de passe : User123!
INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role, actif) VALUES 
('Martin', 'Sophie', 'sophie.martin@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0634567890', '45 Avenue de la République, 33000 Bordeaux', 'utilisateur', TRUE),
('Dupont', 'Pierre', 'pierre.dupont@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0645678901', '78 Cours Victor Hugo, 33000 Bordeaux', 'utilisateur', TRUE);

-- Insertion des plats
INSERT INTO plat (nom_plat, type_plat, description) VALUES 
('Foie gras mi-cuit', 'entree', 'Foie gras maison avec chutney de figues'),
('Saumon fumé', 'entree', 'Saumon fumé d\'Écosse, crème citronnée'),
('Salade de chèvre chaud', 'entree', 'Salade composée, toast de fromage de chèvre'),
('Dinde aux marrons', 'plat', 'Dinde fermière, purée de marrons'),
('Pavé de saumon', 'plat', 'Pavé de saumon, légumes de saison'),
('Risotto aux champignons', 'plat', 'Risotto crémeux, champignons forestiers'),
('Bûche de Noël', 'dessert', 'Bûche chocolat-noisette'),
('Tarte aux pommes', 'dessert', 'Tarte aux pommes maison'),
('Tiramisu', 'dessert', 'Tiramisu traditionnel');

-- Association plats-allergènes
INSERT INTO plat_allergene (id_plat, id_allergene) VALUES 
(1, 1), (1, 4), (2, 5), (3, 1), (3, 2), (5, 5), (6, 2), (9, 1), (9, 4), (9, 2);

-- Insertion des menus
INSERT INTO menu (titre, description, id_theme, id_regime, nb_personnes_min, prix_base, stock_disponible, conditions) VALUES 
('Menu Festif de Noël', 'Un repas traditionnel pour célébrer Noël en famille', 1, 1, 6, 180.00, 10, 'Commande à passer au moins 7 jours avant la livraison. Conservation au réfrigérateur.'),
('Menu Végétarien Raffiné', 'Des saveurs végétales pour un repas équilibré et gourmand', 3, 2, 4, 120.00, 15, 'Commande à passer au moins 5 jours avant la livraison.'),
('Menu Traditionnel', 'Le meilleur de la cuisine française classique', 3, 1, 8, 250.00, 8, 'Commande à passer au moins 10 jours avant la livraison. Matériel de service disponible sur demande.');

-- Association menu-plats
INSERT INTO menu_plat (id_menu, id_plat) VALUES 
(1, 1), (1, 4), (1, 7),
(2, 3), (2, 6), (2, 8),
(3, 2), (3, 5), (3, 9);

-- Images des menus
INSERT INTO image (id_menu, url_image, alt_text, ordre_affichage) VALUES 
(1, '/uploads/menu-noel-1.jpg', 'Menu de Noël - Foie gras', 1),
(1, '/uploads/menu-noel-2.jpg', 'Menu de Noël - Dinde aux marrons', 2),
(2, '/uploads/menu-vegetarien-1.jpg', 'Menu Végétarien - Salade de chèvre', 1),
(3, '/uploads/menu-traditionnel-1.jpg', 'Menu Traditionnel - Saumon', 1);

-- Commandes de test
INSERT INTO commande (id_utilisateur, id_menu, nb_personnes, prix_menu, prix_livraison, prix_total, adresse_livraison, date_livraison, heure_livraison, statut) VALUES 
(3, 1, 8, 216.00, 5.00, 221.00, '45 Avenue de la République, 33000 Bordeaux', '2025-12-24', '18:00:00', 'terminee'),
(4, 2, 6, 144.00, 5.00, 149.00, '78 Cours Victor Hugo, 33000 Bordeaux', '2025-01-15', '19:00:00', 'accepte');

-- Avis validés
INSERT INTO avis (id_commande, id_utilisateur, note, commentaire, valide) VALUES 
(1, 3, 5, 'Excellent repas de Noël ! Toute la famille a adoré. Merci à Julie et José pour leur professionnalisme.', TRUE);

-- =====================================================
-- TRIGGERS
-- =====================================================

-- Trigger pour historique des statuts
DELIMITER //
CREATE TRIGGER after_commande_update
AFTER UPDATE ON commande
FOR EACH ROW
BEGIN
    IF OLD.statut != NEW.statut THEN
        INSERT INTO historique_statut (id_commande, ancien_statut, nouveau_statut)
        VALUES (NEW.id_commande, OLD.statut, NEW.statut);
    END IF;
END//
DELIMITER ;

-- Trigger pour décrémenter le stock
DELIMITER //
CREATE TRIGGER after_commande_accepte
AFTER UPDATE ON commande
FOR EACH ROW
BEGIN
    IF NEW.statut = 'accepte' AND OLD.statut != 'accepte' THEN
        UPDATE menu 
        SET stock_disponible = stock_disponible - 1 
        WHERE id_menu = NEW.id_menu AND stock_disponible > 0;
    END IF;
END//
DELIMITER ;

-- =====================================================
-- VUES
-- =====================================================

-- Vue : Menus complets
CREATE VIEW vue_menus_complets AS
SELECT 
    m.id_menu, m.titre, m.description,
    t.nom_theme, r.nom_regime,
    m.nb_personnes_min, m.prix_base, m.stock_disponible,
    m.conditions, m.actif
FROM menu m
LEFT JOIN theme t ON m.id_theme = t.id_theme
LEFT JOIN regime r ON m.id_regime = r.id_regime
WHERE m.actif = TRUE;

-- Vue : Commandes avec détails
CREATE VIEW vue_commandes_details AS
SELECT 
    c.id_commande,
    u.nom, u.prenom, u.email, u.gsm,
    m.titre AS menu_titre,
    c.nb_personnes, c.prix_total,
    c.date_livraison, c.heure_livraison,
    c.statut, c.date_creation
FROM commande c
JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
JOIN menu m ON c.id_menu = m.id_menu;

SELECT 'Base de données créée avec succès !' AS message;
SELECT COUNT(*) AS nb_utilisateurs FROM utilisateur;
SELECT COUNT(*) AS nb_menus FROM menu;
SELECT COUNT(*) AS nb_plats FROM plat;
SELECT COUNT(*) AS nb_commandes FROM commande;