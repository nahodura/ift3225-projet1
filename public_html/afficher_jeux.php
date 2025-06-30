<?php
session_start();
require_once("includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.<br>";
    echo '<a href="connexion.html">Connexion</a>';
    exit;
}

$erreurMsg = '';
$successMsg = '';
if (isset($_GET['erreur'])) {
    if ($_GET['erreur'] === 'champs') {
        $erreurMsg = 'Données manquantes.';
    } elseif ($_GET['erreur'] === 'autorisation') {
        $erreurMsg = 'Action non autorisée.';
    }
}
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'ajout') {
        $successMsg = 'Le jeu a été ajouté avec succès.';
    } elseif ($_GET['success'] === 'modif') {
        $successMsg = 'Jeu modifié.';
    } elseif ($_GET['success'] === 'suppression') {
        $successMsg = 'Jeu supprimé.';
    }
}

// bar de filtrage
// inspiration de l'extrait de code : 
// https://stackoverflow.com/questions/47486870/how-to-create-filter-for-a-search-in-php
$nom = trim($_GET['nom'] ?? '');
$genre = trim($_GET['genre'] ?? '');
$plateforme = trim($_GET['plateforme'] ?? '');
$description = trim($_GET['description'] ?? '');

$queryDB = "SELECT * FROM jeux WHERE id_utilisateur = ?";
$params = [$_SESSION['id_utilisateur']];

if ($nom !== '') {
    $queryDB .= " AND nom LIKE ?";
    $params[] = "%$nom%";
}
if ($genre !== '') {
    $queryDB .= " AND genre LIKE ?";
    $params[] = "%$genre%";
}
if ($plateforme !== '') {
    $queryDB .= " AND plateforme LIKE ?";
    $params[] = "%$plateforme%";
}
if ($description !== '') {
    $queryDB .= " AND description LIKE ?";
    $params[] = "%$description%";
}

$queryDB .= " ORDER BY date_creation DESC";
$requete = $pdo->prepare($queryDB);
$requete->execute($params);
$jeux = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mes jeux</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="afficher-jeux-container container py-4">

  <?php if ($erreurMsg !== ''): ?>
    <div class="message error"><?php echo $erreurMsg; ?></div>
  <?php elseif ($successMsg !== ''): ?>
    <div class="message success"><?php echo $successMsg; ?></div>
  <?php endif; ?>

  <h2 class="titre-section mb-4">Mes jeux</h2>
  <a href="ajouter_jeux.html" class="btn btn-purple btn-add-game w-100 mb-3">Ajouter un jeu</a>

  <h3>Filtrer</h3>
  <form method="GET" action="afficher_jeux.php" class="row g-3 mb-4 form-style filter-form">
    <div class="col-md-3">
      <label for="nom" class="form-label">Nom</label>
      <input type="text" name="nom" id="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>">
    </div>
    <div class="col-md-3">
      <label for="genre" class="form-label">Genre</label>
      <input type="text" name="genre" id="genre" class="form-control" value="<?php echo htmlspecialchars($genre); ?>">
    </div>
    <div class="col-md-3">
      <label for="plateforme" class="form-label">Plateforme</label>
      <input type="text" name="plateforme" id="plateforme" class="form-control" value="<?php echo htmlspecialchars($plateforme); ?>">
    </div>
    <div class="col-md-3">
      <label for="description" class="form-label">Description</label>
      <input type="text" name="description" id="description" class="form-control" value="<?php echo htmlspecialchars($description); ?>">
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-secondary">Filtrer</button>
    </div>
  </form>

  <div id="jeuxContainer" class="row jeux-grid"></div>

<template id="jeu-template">
    <div class="col-md-4 mb-4">
      <div class="jeu-card">
        <strong class="nom d-block mb-1"></strong>
        <span class="genre d-block"></span>
        <span class="plateforme d-block"></span>
        <span class="description d-block mb-2"></span>
        <img class="image img-fluid mb-3" alt="">
        <div class="card-actions">
          <button class="btn btn-purple w-100 mb-2 edit">Modifier</button>
          <form class="delete-form mt-1">
            <input type="hidden" name="jeu_id">
            <button type="submit" class="btn btn-danger w-100">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </template>

  <div class="d-flex justify-content-between align-items-center mt-5">
    <a href="index.php" class="return-link">Retour au menu</a>
    <form method="POST" action="api/authentification/deconnexion.php">
      <button type="submit" class="logout-button">Déconnexion</button>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/jeux.js"></script>
</body>
</html>
