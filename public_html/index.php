<?php
session_start();

if (!isset($_SESSION['id_utilisateur']) || !isset($_SESSION['nom_utilisateur'])) {
    header("Location: connexion.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="centered-page">
    <div class="card home-card">
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom_utilisateur']); ?><?php if (!empty($_SESSION['is_admin'])) echo ' (Admin)'; ?> !</h1>
        <a href="ajouter_jeux.html" class="action-button blue-button"> Ajouter un jeu</a>
        <a href="afficher_jeux.php" class="action-button blue-button"> Voir mes jeux</a>
        <form action="api/authentification/deconnexion.php" method="POST">
            <button type="submit" class="logout-button">Déconnexion</button>
        </form>
    </div>
</body>
</html>

