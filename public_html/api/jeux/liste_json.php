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
$nom = trim($_GET['nom'] ?? '');
$genre = trim($_GET['genre'] ?? '');
$plateforme = trim($_GET['plateforme'] ?? '');
$description = trim($_GET['description'] ?? '');

$query = "SELECT * FROM jeux WHERE id_utilisateur = ?";
$params = [$_SESSION['id_utilisateur']];

if ($nom !== '') {
    $query .= " AND nom LIKE ?";
    $params[] = "%$nom%";
}
if ($genre !== '') {
    $query .= " AND genre LIKE ?";
    $params[] = "%$genre%";
}
if ($plateforme !== '') {
    $query .= " AND plateforme LIKE ?";
    $params[] = "%$plateforme%";
}
if ($description !== '') {
    $query .= " AND description LIKE ?";
    $params[] = "%$description%";
}

$query .= " ORDER BY date_creation DESC";
$requete = $pdo->prepare($query);
$requete->execute($params);

$jeux = $requete->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($jeux);