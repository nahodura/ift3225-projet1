<?php
session_start();
require_once("includes/db_inc.php");

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexion.html");
    exit;
}

$jeu_id = $_GET['id'] ?? '';
if ($jeu_id === '') {
    header("Location: afficher_jeux.php?erreur=autorisation");
    exit;
}

$requete = $pdo->prepare("SELECT * FROM jeux WHERE jeu_id = ? AND id_utilisateur = ?");
$requete->execute([$jeu_id, $_SESSION['id_utilisateur']]);
$jeu = $requete->fetch(PDO::FETCH_ASSOC);

if (!$jeu) {
    header("Location: afficher_jeux.php?erreur=autorisation");
    exit;
}

$erreurMsg = '';
if (isset($_GET['erreur']) && $_GET['erreur'] === 'champs') {
    $erreurMsg = 'Données manquantes.';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Modifier le jeu</title>
</head>
<body>
  <div class="form-container">
    <h2>Modifier le jeu</h2>
    <?php if ($erreurMsg !== ''): ?>
      <p class="message error"><?php echo $erreurMsg; ?></p>
    <?php endif; ?>
    <form method="POST" action="api/jeux/modifier.php" enctype="multipart/form-data">
      <input type="hidden" name="jeu_id" value="<?php echo htmlspecialchars($jeu['jeu_id']); ?>" />
      <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($jeu['image']); ?>" />
      <label for="nom">Nom du jeu</label>
      <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($jeu['nom']); ?>" required />

      <label for="genre">Genre</label>
      <input type="text" name="genre" id="genre" value="<?php echo htmlspecialchars($jeu['genre']); ?>" />

      <label for="plateforme">Plateforme</label>
      <input type="text" name="plateforme" id="plateforme" value="<?php echo htmlspecialchars($jeu['plateforme']); ?>" />

      <label for="description">Description</label>
      <textarea name="description" id="description" rows="4"><?php echo htmlspecialchars($jeu['description']); ?></textarea>

      <?php if ($jeu['image']): ?>
        <img src="img/<?php echo htmlspecialchars($jeu['image']); ?>" width="150" alt="<?php echo htmlspecialchars($jeu['nom']); ?>" /><br>
      <?php endif; ?>
      <label for="image">Changer l'image</label>
      <input type="file" name="image" id="image" />

      <button type="submit">Enregistrer</button>
    </form>
    <p class="switch-link">
      <a href="afficher_jeux.php">Retour à la liste</a>
    </p>
  </div>
</body>
</html>