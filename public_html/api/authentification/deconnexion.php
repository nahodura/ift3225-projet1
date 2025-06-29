<?php
session_start();
require_once("../../includes/db_inc.php");

if (isset($_SESSION['id_utilisateur'])) {
    $session_id = session_id();

    $requete = $pdo->prepare("DELETE FROM account_sessions WHERE session_id = ?");
    $requete->execute([$session_id]);

    session_destroy();
    header("Location: ../../connexion.html?success=deconnexion");
    exit;
} else {
    header("Location: ../../connexion.html?success=deconnexion");
    exit;
}

