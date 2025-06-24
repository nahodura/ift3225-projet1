<?php
session_start();
require_once("../../includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    echo "Non autorisé.";
    exit;
}

$requete = $pdo->prepare("SELECT * FROM jeux WHERE id_utilisateur = ?");
$requete->execute([$_SESSION['id_utilisateur']]);
$jeux = $requete->fetchAll(PDO::FETCH_ASSOC);

if (empty($jeux)) {
    echo "Aucun jeu trouvé.";
    exit;
}

foreach ($jeux as $jeu) {
    echo "<div>";
    echo "<strong>" . htmlspecialchars($jeu['nom']) . "</strong><br>";
    echo "Genre : " . htmlspecialchars($jeu['genre']) . "<br>";
    echo "Plateforme : " . htmlspecialchars($jeu['plateforme']) . "<br>";
    echo "Description : " . nl2br(htmlspecialchars($jeu['description'])) . "<br>";
    if ($jeu['image']) {
        echo "<img src='../img/" . htmlspecialchars($jeu['image']) . "' width='100'/><br>";
    }
    echo "<hr>";
    echo "</div>";
}
?>
