<?php

$host = 'localhost';  
$user = 'durandna';        
$schema = 'durandna_projet1';
$db_password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$schema;charset=utf8mb4",
        $user,
        $db_password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données.";
    die();
}
?>
