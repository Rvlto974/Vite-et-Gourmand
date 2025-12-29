<?php
$conn = new PDO('mysql:host=db;dbname=vite_gourmand', 'root', 'root_password');
$hash = password_hash('password', PASSWORD_DEFAULT);
$stmt = $conn->prepare('UPDATE utilisateur SET mot_de_passe = ? WHERE email = ?');
$stmt->execute([$hash, 'test@test.fr']);
echo "Mot de passe mis à jour !";