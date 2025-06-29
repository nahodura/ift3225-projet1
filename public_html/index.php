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
  <?php if (isset($_GET['success'])): ?>
    <p class="message success">
      <?php if ($_GET['success'] === 'connexion') echo 'Connexion réussie.'; ?>
      <?php if ($_GET['success'] === 'inscription') echo 'Compte créé.'; ?>
    </p>
  <?php endif; 
  ?>
  <a href="ajouter_jeux.html">Ajouter un jeu</a><br>
  <a href="afficher_jeux.php">Voir mes jeux</a><br>
  <form method="POST" action="api/authentification/deconnexion.php">
      <button type="submit">Déconnexion</button>
  </form>
</body>
</html>
