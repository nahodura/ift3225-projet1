<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../../connexion.html");
    exit;
}

$jeu_id = $_POST['jeu_id'] ?? '';
$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$genre = $_POST['genre'] ?? '';
$plateforme = $_POST['plateforme'] ?? '';
$image = $_POST['current_image'] ?? '';
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

if ($jeu_id === '' || $nom === '') {
    header("Location: ../../afficher_jeux.php?erreur=champs");
    exit;
}

$verif = $pdo->prepare("SELECT jeu_id FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$verif->execute([$jeu_id, $_SESSION['id_utilisateur']]);
if (!$verif->fetch()) {
    header("Location: ../../afficher_jeux.php?erreur=autorisation");
    exit;
}

$requete = $pdo->prepare("UPDATE jeux SET nom = ?, description = ?, genre = ?, plateforme = ?, image = ? WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$nom, $description, $genre, $plateforme, $image, $jeu_id, $_SESSION['id_utilisateur']]);

header("Location: ../../afficher_jeux.php?success=modif");
exit;

