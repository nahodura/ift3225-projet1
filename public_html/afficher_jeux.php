<?php
session_start();
require_once("includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.<br>";
    echo '<a href="connexion.html">Connexion</a>';
    exit;
}

// dans les cas de redirection
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

// requête avec le filtrage 
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes jeux</title>
</head>
<body>
<div id="message" class="message">
<?php if ($erreurMsg !== ''): ?>
  <?php echo $erreurMsg; ?>
<?php elseif ($successMsg !== ''): ?>
  <?php echo $successMsg; ?>
<?php endif; ?>
</div>
<h2>Mes jeux</h2>
<div class="form-container">
  <h2>Ajouter un jeu</h2>
  <form id="addForm" enctype="multipart/form-data">
    <label for="add_nom">Nom du jeu</label>
    <input type="text" name="nom" id="add_nom" required />
    <label for="add_genre">Genre</label>
    <input type="text" name="genre" id="add_genre" />
    <label for="add_plateforme">Plateforme</label>
    <input type="text" name="plateforme" id="add_plateforme" />
    <label for="add_description">Description</label>
    <textarea name="description" id="add_description" rows="4"></textarea>
    <label for="add_image">Image</label>
    <input type="file" name="image" id="add_image" />
    <button type="submit">Ajouter</button>
  </form>
</div>
<br>
<div class="form-container">
  <h2>Filtrer</h2>
  <form method="GET" action="afficher_jeux.php">
    <label for="nom">Nom</label>
    <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($nom); ?>" />
    <label for="genre">Genre</label>
    <input type="text" name="genre" id="genre" value="<?php echo htmlspecialchars($genre); ?>" />
    <label for="plateforme">Plateforme</label>
    <input type="text" name="plateforme" id="plateforme" value="<?php echo htmlspecialchars($plateforme); ?>" />
    <label for="description">Description</label>
    <input type="text" name="description" id="description" value="<?php echo htmlspecialchars($description); ?>" />
  </form>
  <br>
</div>
<div id="jeuxContainer"></div>

<template id="jeu-template">
  <div class="jeu">
    <strong class="nom"></strong><br>
    <span class="genre"></span><br>
    <span class="plateforme"></span><br>
    <span class="description"></span><br>
    <img class="image" width="100" style="display:none" /><br>
    <button class="edit">Modifier</button>
    <form class="delete-form" style="display:inline">
      <input type="hidden" name="jeu_id" value="" />
      <button type="submit">Supprimer</button>
    </form>
    <hr>
  </div>
</template>
<a href="index.php">Retour au menu</a><br>
<form method="POST" action="api/authentification/deconnexion.php">
  <button type="submit">Déconnexion</button>
</form>
<script src="js/jeux.js"></script>
</body>
</html>