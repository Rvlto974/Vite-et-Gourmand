<?php
try {
    $manager = new MongoDB\Driver\Manager("mongodb://mongo:27017");
    echo "MongoDB connexion OK !<br>";
    
    // Test d'insertion
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert(['test' => 'data', 'timestamp' => new MongoDB\BSON\UTCDateTime()]);
    $manager->executeBulkWrite('vite_gourmand.analytics', $bulk);
    echo "Insertion OK !<br>";
    
    // Test de lecture
    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery('vite_gourmand.analytics', $query);
    $count = 0;
    foreach ($cursor as $doc) {
        $count++;
    }
    echo "Nombre de documents : $count<br>";
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}