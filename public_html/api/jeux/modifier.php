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

$requete = $pdo->prepare("UPDATE jeux SET nom = ?, description = ?, genre = ?, plateforme = ?, image = ? WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$nom, $description, $genre, $plateforme, $image, $jeu_id, $_SESSION['id_utilisateur']]);

echo json_encode([
    'success' => true,
    'jeu' => [
        'jeu_id' => $jeu_id,
        'nom' => $nom,
        'description' => $description,
        'genre' => $genre,
        'plateforme' => $plateforme,
        'image' => $image
    ]
]);
exit;
