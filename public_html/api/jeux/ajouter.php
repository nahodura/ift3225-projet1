<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../../connexion.html");
    exit;
}

$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$genre = $_POST['genre'] ?? '';
$plateforme = $_POST['plateforme'] ?? '';
$image = '';
if (!empty($_FILES['image']['name'])) {
    $targetDir = __DIR__ . '/../../img/';
    $nomFichier = basename($_FILES['image']['name']);
    $nomFichier = preg_replace('/[^a-zA-Z0-9._-]/', '_', $nomFichier);
    $chemin = $targetDir . uniqid('', true) . '_' . $nomFichier;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $chemin)) {
        $image = basename($chemin);
    }
}

if ($image === '') {
    $image = 'no_image.png';
}

if ($nom === '') {
    header("Location: ../../ajouter_jeux.html?erreur=champs");
    exit;
}

$requete = $pdo->prepare("INSERT INTO jeux (nom, description, genre, plateforme, image, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?)");
$requete->execute([$nom, $description, $genre, $plateforme, $image, $_SESSION['id_utilisateur']]);

header("Location: ../../afficher_jeux.php?success=ajout");
exit;

