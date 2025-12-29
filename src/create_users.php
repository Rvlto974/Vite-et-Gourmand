<?php
$conn = new PDO('mysql:host=db;dbname=vite_gourmand', 'root', 'root_password');

$users = [
    ['Jose', 'Dupont', 'jose@vitegourmand.fr', 'Admin123!', '0556000001', '10 Rue du Traiteur, Bordeaux', 'admin'],
    ['Julie', 'Martin', 'julie@vitegourmand.fr', 'Employe123!', '0556000002', '10 Rue du Traiteur, Bordeaux', 'employe'],
    ['Sophie', 'Martin', 'sophie.martin@email.fr', 'User123!', '0612345678', '15 Avenue de la République, Bordeaux', 'utilisateur']
];

foreach ($users as $user) {
    $hash = password_hash($user[3], PASSWORD_DEFAULT);
    try {
        $stmt = $conn->prepare('INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, gsm, adresse_postale, role) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user[0], $user[1], $user[2], $hash, $user[4], $user[5], $user[6]]);
        echo "✅ {$user[2]}\n";
    } catch (PDOException $e) {
        echo "❌ {$user[2]}: {$e->getMessage()}\n";
    }
}