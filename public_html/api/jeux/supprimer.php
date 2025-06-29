<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: ../../connexion.html");
    exit;
}

$jeu_id = $_POST['jeu_id'] ?? '';

if ($jeu_id === '') {
    header("Location: ../../afficher_jeux.php?erreur=champs");
    exit;
}

$verif = $pdo->prepare("SELECT jeu_id FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$verif->execute([$jeu_id, $_SESSION['id_utilisateur']]);
if (!$verif->fetch()) {
    header("Location: ../../afficher_jeux.php?erreur=autorisation");
    exit;
}

$requete = $pdo->prepare("DELETE FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$jeu_id, $_SESSION['id_utilisateur']]);

header("Location: ../../afficher_jeux.php?success=suppression");
exit;

