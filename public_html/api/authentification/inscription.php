<?php
session_start();
require_once("../../includes/db_inc.php");

$nom        = trim($_POST['nom']        ?? '');
$email      = trim($_POST['email']      ?? '');
$motDePasse = trim($_POST['motDePasse'] ?? '');

if ($nom === '' || $motDePasse === '' || $email === '') {
    header("Location: ../../inscription.html?erreur=champs");
    exit;
}

// pour validation email : https://www.php.net/manual/en/function.filter-var.php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../inscription.html?erreur=email");
    exit;
}

// si username ou email existe déjà
$requete = $pdo->prepare("SELECT account_id FROM accounts WHERE account_name = ? OR account_email = ?");
$requete->execute([$nom, $email]);
if ($requete->fetch()) {
    header("Location: ../../inscription.html?erreur=existant");
    exit;
}

$hash = password_hash($motDePasse, PASSWORD_DEFAULT);

$requete = $pdo->prepare("INSERT INTO accounts (account_name, account_email, account_passwd) VALUES (?, ?, ?)");
$requete->execute([$nom, $email, $hash]);

$id_utilisateur = $pdo->lastInsertId();

// insére 15 jeux par défaut
$jeuxParDefault = [
    ['Zelda', 'Adventure game', 'Adventure', 'Switch', 'zelda.jpg'],
    ['Minecraft', 'Sandbox building game', 'Sandbox', 'PC', 'minecraft.jpeg'],
    ['Among Us', 'Multiplayer deduction game', 'Party', 'Mobile', 'amongus.jpg'],
    ['Super Mario Odyssey', '3D platformer', 'Platformer', 'Switch', 'mario.jpg'],
    ['Stardew Valley', 'Farming simulation', 'Simulation', 'PC', 'stardew.jpeg'],
    ['Overwatch', 'Team shooter', 'FPS', 'PC', 'overwatch.jpg'],
    ['Fortnite', 'Battle royale', 'Battle Royale', 'PC', 'fortnite.jpeg'],
    ['Celeste', 'Challenging platformer', 'Platformer', 'Switch', 'celeste.jpeg'],
    ['Animal Crossing', 'Life simulation', 'Simulation', 'Switch', 'animalcrossing.jpg'],
    ['Hades', 'Action roguelike', 'Action', 'Switch', 'hades.jpg'],
    ['Apex Legends', 'Hero shooter battle royale', 'Battle Royale', 'PC', 'apexlegends.jpeg'],
    ['Halo Infinite', 'Sci-fi shooter', 'FPS', 'Xbox', 'haloinfinite.jpeg'],
    ['The Witcher 3', 'Fantasy RPG', 'RPG', 'PC', 'witcherIII.jpg'],
    ['Portal 2', 'Puzzle platformer', 'Puzzle', 'PC', 'portal2.jpeg'],
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

header("Location: ../../index.php?success=inscription");
exit;

