<?php
session_start();
require_once("includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.<br>";
    echo '<a href="connexion.html">Connexion</a>';
    exit;
}

$requete = $pdo->prepare("SELECT * FROM jeux WHERE id_utilisateur = ?");
$requete->execute([$_SESSION['id_utilisateur']]);
$jeux = $requete->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Mes jeux</h2>";
echo '<a href="ajouter_jeu.html">Ajouter un jeu</a><br><br>';

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

    // Formulaire de suppression
    echo "<form method='POST' action='api/jeux/supprimer.php' style='display:inline'>";
    echo "<input type='hidden' name='jeu_id' value='".htmlspecialchars($jeu['jeu_id'])."'>";
    echo "<button type='submit' onclick='return confirm(\"Confirmer la suppression ?\");'>Supprimer</button>";
    echo "</form>";

    echo "<hr>";
}
?>
