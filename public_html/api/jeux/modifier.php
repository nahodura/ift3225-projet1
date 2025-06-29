<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.";
    exit;
}

$jeu_id = $_POST['jeu_id'] ?? '';
$nom = $_POST['nom'] ?? '';
$description = $_POST['description'] ?? '';
$genre = $_POST['genre'] ?? '';
$plateforme = $_POST['plateforme'] ?? '';
$image = $_POST['current_image'] ?? '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . '/../../img/';
    $fichier = basename($_FILES['image']['name']);
    $cheminDestination = $uploadDir . $fichier;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $cheminDestination)) {
        $image = $fichier;
    }
}

if ($jeu_id === '' || $nom === '') {
    echo "Données manquantes.";
    exit;
}

$verif = $pdo->prepare("SELECT jeu_id FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$verif->execute([$jeu_id, $_SESSION['id_utilisateur']]);
if (!$verif->fetch()) {
    echo "Modification non autorisée.";
    exit;
}

$requete = $pdo->prepare("UPDATE jeux SET nom = ?, description = ?, genre = ?, plateforme = ?, image = ? WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$nom, $description, $genre, $plateforme, $image, $jeu_id, $_SESSION['id_utilisateur']]);

echo "Jeu modifié.";
?>
