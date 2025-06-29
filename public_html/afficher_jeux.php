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
$date_debut = trim($_GET['date_debut'] ?? '');
$date_fin = trim($_GET['date_fin'] ?? '');

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
if ($date_debut !== '') {
    $queryDB .= " AND DATE(date_creation) >= ?";
    $params[] = $date_debut;
}
if ($date_fin !== '') {
    $queryDB .= " AND DATE(date_creation) <= ?";
    $params[] = $date_fin;
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
<?php if ($erreurMsg !== ''): ?>
  <p class="message error"><?php echo $erreurMsg; ?></p>
<?php endif; ?>
<?php if ($successMsg !== ''): ?>
  <p class="message success"><?php echo $successMsg; ?></p>
<?php endif; ?>
<h2>Mes jeux</h2>
<a href="ajouter_jeux.html">Ajouter un jeu</a><br><br>
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
    <label for="date_debut">Date début</label>
    <input type="date" name="date_debut" id="date_debut" value="<?php echo htmlspecialchars($date_debut); ?>" />
    <label for="date_fin">Date fin</label>
    <input type="date" name="date_fin" id="date_fin" value="<?php echo htmlspecialchars($date_fin); ?>" />
    <button type="submit">Filtrer</button>
  </form>
</div>
<?php foreach ($jeux as $jeu): ?>
  <form method="POST" action="api/jeux/modifier.php">
    <input type="hidden" name="jeu_id" value="<?php echo htmlspecialchars($jeu['jeu_id']); ?>">
    <strong>Nom:</strong> <input type="text" name="nom" value="<?php echo htmlspecialchars($jeu['nom']); ?>"><br>
    <strong>Genre:</strong> <input type="text" name="genre" value="<?php echo htmlspecialchars($jeu['genre']); ?>"><br>
    <strong>Plateforme:</strong> <input type="text" name="plateforme" value="<?php echo htmlspecialchars($jeu['plateforme']); ?>"><br>
    <strong>Description:</strong> <textarea name="description"><?php echo htmlspecialchars($jeu['description']); ?></textarea><br>
    <strong>Image:</strong> <input type="text" name="image" value="<?php echo htmlspecialchars($jeu['image']); ?>"><br>
    <button type="submit">Modifier</button>
  </form>
  <form method="POST" action="api/jeux/supprimer.php" style="display:inline">
    <input type="hidden" name="jeu_id" value="<?php echo htmlspecialchars($jeu['jeu_id']); ?>">
    <button type="submit" onclick="return confirm('Confirmer la suppression ?');">Supprimer</button>
  </form>
  <hr>
<?php endforeach; ?>
<a href="index.php">Retour au menu</a><br>
<form method="POST" action="api/authentification/deconnexion.php">
  <button type="submit">Déconnexion</button>
</form>
</body>
</html>