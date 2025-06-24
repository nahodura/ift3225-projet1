<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.";
    exit;
}

$jeu_id = $_POST['jeu_id'] ?? '';

if ($jeu_id === '') {
    echo "Données manquantes.";
    exit;
}

$verif = $pdo->prepare("SELECT jeu_id FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$verif->execute([$jeu_id, $_SESSION['id_utilisateur']]);
if (!$verif->fetch()) {
    echo "Suppression non autorisée.";
    exit;
}

$requete = $pdo->prepare("DELETE FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$jeu_id, $_SESSION['id_utilisateur']]);

echo "Le jeu supprimé avec succès.";
?>
