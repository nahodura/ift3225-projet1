<?php
session_start();
require_once("../../includes/db_inc.php");

header('Content-Type: application/json');
if (!isset($_SESSION['id_utilisateur'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'unauthorized']);
    exit;
}

$jeu_id = $_POST['jeu_id'] ?? '';

if ($jeu_id === '') {
    echo json_encode(['success' => false, 'error' => 'missing_fields']);
    exit;
}

$verif = $pdo->prepare("SELECT jeu_id FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$verif->execute([$jeu_id, $_SESSION['id_utilisateur']]);
if (!$verif->fetch()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'forbidden']);
    exit;
}

$requete = $pdo->prepare("DELETE FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$jeu_id, $_SESSION['id_utilisateur']]);

echo json_encode(['success' => true]);
exit;