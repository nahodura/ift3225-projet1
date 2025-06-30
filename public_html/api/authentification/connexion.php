<?php
session_start();
require_once("../../includes/db_inc.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

   if ($email === '' || $motDePasse === '') {
        header("Location: ../../connexion.html?erreur=champs");
        exit;
    }

    $requete = $pdo->prepare("SELECT account_id, account_name, account_passwd, account_admin FROM accounts WHERE account_email = ? AND account_enabled = 1");
    $requete->execute([$email]);
    $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

    // nouvelle session
    if ($utilisateur && password_verify($motDePasse, $utilisateur['account_passwd'])) {

        $_SESSION['id_utilisateur'] = $utilisateur['account_id'];
        $_SESSION['nom_utilisateur'] = $utilisateur['account_name'];
        $_SESSION['is_admin'] = $utilisateur['account_admin'];

        // enregistre la session 
        $session_id = session_id();
        $requete2 = $pdo->prepare("REPLACE INTO account_sessions (session_id, account_id, login_time) VALUES (?, ?, NOW())");
        $requete2->execute([$session_id, $utilisateur['account_id']]);

        header("Location: ../../index.php?success=connexion");
        exit;
    } else {
        header("Location: ../../connexion.html?erreur=identifiants");
        exit;
    }
}

