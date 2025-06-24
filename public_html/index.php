<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.html");
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil</title>
</head>
<body>
  <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom_utilisateur']); ?> !</h1>
  <a href="ajouter_jeu.html">Ajouter un jeu</a><br>
  <a href="lister_jeux.php">Voir mes jeux</a><br>
  <form method="POST" action="api/authentification/deconnexion.php">
      <button type="submit">Déconnexion</button>
  </form>
</body>
</html>
