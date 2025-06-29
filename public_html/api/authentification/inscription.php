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

// nouvelle session
$_SESSION['id_utilisateur'] = $id_utilisateur;
$_SESSION['nom_utilisateur'] = $nom;

$session_id = session_id();
$requete = $pdo->prepare("REPLACE INTO account_sessions (session_id, account_id, login_time) VALUES (?, ?, NOW())");
$requete->execute([$session_id, $id_utilisateur]);

header("Location: ../../index.php");
exit;
?>
