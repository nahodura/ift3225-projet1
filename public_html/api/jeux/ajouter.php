<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.";
    exit;
}

$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$genre = $_POST['genre'] ?? '';
$plateforme = $_POST['plateforme'] ?? '';
$image = $_POST['image'] ?? '';

if ($nom === '') {
    echo "Le nom est requis.";
    exit;
}

$requete = $pdo->prepare("INSERT INTO jeux (nom, description, genre, plateforme, image, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?)");
$requete->execute([$nom, $description, $genre, $plateforme, $image, $_SESSION['id_utilisateur']]);

echo "Le jeu a été ajouté avec succès.";
?>
