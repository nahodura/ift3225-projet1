<?php
session_start();
require_once("includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.<br>";
    echo '<a href="connexion.html">Connexion</a>';
    exit;
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

echo "<h2>Mes jeux</h2>";
echo '<a href="ajouter_jeux.html">Ajouter un jeu</a><br><br>';

echo '<div class="form-container">';
echo '<h2>Filtrer</h2>';
echo '<form method="GET" action="afficher_jeux.php">';
echo '<label for="nom">Nom</label>';
echo '<input type="text" name="nom" id="nom" value="'.htmlspecialchars($nom).'" />';
echo '<label for="genre">Genre</label>';
echo '<input type="text" name="genre" id="genre" value="'.htmlspecialchars($genre).'" />';
echo '<label for="plateforme">Plateforme</label>';
echo '<input type="text" name="plateforme" id="plateforme" value="'.htmlspecialchars($plateforme).'" />';
echo '<label for="description">Description</label>';
echo '<input type="text" name="description" id="description" value="'.htmlspecialchars($description).'" />';
echo '<label for="date_debut">Date début</label>';
echo '<input type="date" name="date_debut" id="date_debut" value="'.htmlspecialchars($date_debut).'" />';
echo '<label for="date_fin">Date fin</label>';
echo '<input type="date" name="date_fin" id="date_fin" value="'.htmlspecialchars($date_fin).'" />';
echo '<button type="submit">Filtrer</button>';
echo '</form>';
echo '</div>';

foreach ($jeux as $jeu) {
    echo "<form method='POST' action='api/jeux/modifier.php'>";
    echo "<input type='hidden' name='jeu_id' value='".htmlspecialchars($jeu['jeu_id'])."'>";
    echo "<strong>Nom:</strong> <input type='text' name='nom' value='".htmlspecialchars($jeu['nom'])."'><br>";
    echo "<strong>Genre:</strong> <input type='text' name='genre' value='".htmlspecialchars($jeu['genre'])."'><br>";
    echo "<strong>Plateforme:</strong> <input type='text' name='plateforme' value='".htmlspecialchars($jeu['plateforme'])."'><br>";
    echo "<strong>Description:</strong> <textarea name='description'>".htmlspecialchars($jeu['description'])."</textarea><br>";
    echo "<strong>Image:</strong> <input type='text' name='image' value='".htmlspecialchars($jeu['image'])."'><br>";
    echo "<button type='submit'>Modifier</button>";
    echo "</form>";

    // formulaire de suppression
    echo "<form method='POST' action='api/jeux/supprimer.php' style='display:inline'>";
    echo "<input type='hidden' name='jeu_id' value='".htmlspecialchars($jeu['jeu_id'])."'>";
    echo "<button type='submit' onclick='return confirm(\"Confirmer la suppression ?\");'>Supprimer</button>";
    echo "</form>";

    echo "<hr>";
    }

echo '<a href="index.php">Retour au menu</a><br>';
echo '<form method="POST" action="api/authentification/deconnexion.php">';
echo '<button type="submit">Déconnexion</button>';
echo '</form>';
