<?php

class User {
    private $conn;
    private $table = 'utilisateur';

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $gsm;
    public $adresse_postale;
    public $role;
    public $actif;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Inscription
    public function register() {
        $query = "  INSERT INTO " . $this->table . " 
                    (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role, actif) 
                    VALUES (:nom, :prenom, :email, :mot_de_passe, :gsm, :adresse_postale, 'utilisateur', 1)";

        $stmt = $this->conn->prepare($query);

        // Hash du mot de passe
        $hashed_password = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);

        // Binding
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':prenom', $this->prenom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':mot_de_passe', $hashed_password);
        $stmt->bindParam(':gsm', $this->gsm);
        $stmt->bindParam(':adresse_postale', $this->adresse_postale);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Vérifier si email existe déjà
    public function emailExists() {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $this->id = $row['id_utilisateur'];
            $this->nom = $row['nom'];
            $this->prenom = $row['prenom'];
            $this->mot_de_passe = $row['mot_de_passe'];
            $this->gsm = $row['gsm'];
            $this->adresse_postale = $row['adresse_postale'];
            $this->role = $row['role'];
            $this->actif = $row['actif'];
            return true;
        }
        return false;
    }

    // Connexion
    public function login($password) {
        if ($this->emailExists()) {
            if (password_verify($password, $this->mot_de_passe) && $this->actif) {
                return true;
            }
        }
        return false;
    }
}