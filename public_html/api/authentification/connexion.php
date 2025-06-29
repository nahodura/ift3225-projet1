<?php
session_start();
require_once("../../includes/db_inc.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

   if ($nom === '' || $motDePasse === '') {
        header("Location: ../../connexion.html?erreur=champs");
        exit;
    }

    $requete = $pdo->prepare("SELECT account_id, account_passwd FROM accounts WHERE account_name = ? AND account_enabled = 1");
    $requete->execute([$nom]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    // nouvelle session
    if ($utilisateur && password_verify($motDePasse, $utilisateur['account_passwd'])) {

        $_SESSION['id_utilisateur'] = $utilisateur['account_id'];
        $_SESSION['nom_utilisateur'] = $nom;

        // enregistre la session 
        $session_id = session_id();
        $requete2 = $pdo->prepare("REPLACE INTO account_sessions (session_id, account_id, login_time) VALUES (?, ?, NOW())");
        $requete2->execute([$session_id, $utilisateur['account_id']]);

        header("Location: ../../index.php");
        exit;
    } else {
        header("Location: ../../connexion.html?erreur=identifiants");
        exit;
    }
}

