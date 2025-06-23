<?php
require_once(__DIR__ . '/pwd.php');

$host = 'www-ens.iro.umontreal.ca';  
$user = 'durandna';        
$schema = 'durandna_ift3225_projet1';  


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
