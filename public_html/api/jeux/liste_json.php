<?php
session_start();
require_once("../../includes/db_inc.php");

header('Content-Type: application/json');

if (!isset($_SESSION['id_utilisateur'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'unauthorized']);
    exit;
}

// retourne liste des jeux de l'utilisateur en format JSON

$requete = $pdo->prepare("SELECT * FROM jeux WHERE id_utilisateur = ? ORDER BY date_creation DESC");
$requete->execute([$_SESSION['id_utilisateur']]);
$jeux = $requete->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jeux);