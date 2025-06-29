<?php
session_start();
require_once("../../includes/db_inc.php");

$nom        = trim($_POST['nom']        ?? '');
$motDePasse = trim($_POST['motDePasse'] ?? '');

if ($nom === '' || $motDePasse === '') {
    echo "Veuillez remplir tous les champs.";
    exit;
}

// si username existe déjà
$requete = $pdo->prepare("SELECT account_id FROM accounts WHERE account_name = ?");
$requete->execute([$nom]);
if ($requete->fetch()) {
    echo "Nom d'utilisateur déjà utilisé. <a href='../../inscription.html'>Réessayer</a>";
    exit;
}

$hash = password_hash($motDePasse, PASSWORD_DEFAULT);

$requete = $pdo->prepare("INSERT INTO accounts (account_name, account_passwd) VALUES (?, ?)");
$requete->execute([$nom, $hash]);

$id_utilisateur = $pdo->lastInsertId();

// insére 15 jeux par défaut
$jeuxParDefault = [
    ['Zelda', 'Adventure game', 'Adventure', 'Switch', 'zelda.jpg'],
    ['Minecraft', 'Sandbox building game', 'Sandbox', 'PC', 'minecraft.jpg'],
    ['Among Us', 'Multiplayer deduction game', 'Party', 'Mobile', 'amongus.jpg'],
    ['Super Mario Odyssey', '3D platformer', 'Platformer', 'Switch', 'mario.jpg'],
    ['Stardew Valley', 'Farming simulation', 'Simulation', 'PC', 'stardew.jpg'],
    ['Overwatch', 'Team shooter', 'FPS', 'PC', 'overwatch.jpg'],
    ['Fortnite', 'Battle royale', 'Battle Royale', 'PC', 'fortnite.jpg'],
    ['Celeste', 'Challenging platformer', 'Platformer', 'Switch', 'celeste.jpg'],
    ['Animal Crossing', 'Life simulation', 'Simulation', 'Switch', 'acnh.jpg'],
    ['Hades', 'Action roguelike', 'Action', 'Switch', 'hades.jpg'],
    ['Apex Legends', 'Hero shooter battle royale', 'Battle Royale', 'PC', 'apex.jpg'],
    ['Halo Infinite', 'Sci-fi shooter', 'FPS', 'Xbox', 'haloinfinite.jpg'],
    ['The Witcher 3', 'Fantasy RPG', 'RPG', 'PC', 'witcher3.jpg'],
    ['Portal 2', 'Puzzle platformer', 'Puzzle', 'PC', 'portal2.jpg'],
    ['Cyberpunk 2077', 'Open world RPG', 'RPG', 'PC', 'cyberpunk.jpg']
];

$insertJeu = $pdo->prepare("INSERT INTO jeux (nom, description, genre, plateforme, image, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?)");
foreach ($jeuxParDefault as $game) {
    $insertJeu->execute(array_merge($game, [$id_utilisateur]));
}

// nouvelle session
$_SESSION['id_utilisateur'] = $id_utilisateur;
$_SESSION['nom_utilisateur'] = $nom;

$session_id = session_id();
$requete = $pdo->prepare("REPLACE INTO account_sessions (session_id, account_id, login_time) VALUES (?, ?, NOW())");
$requete->execute([$session_id, $id_utilisateur]);

header("Location: ../../index.php");
exit;
?>
