<?php
require_once("../../includes/db_inc.php"); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'] ?? '';
    $motDePasse = $_POST['motDePasse'] ?? '';

    if ($nom === '' || $motDePasse === '') {
        echo "Veuillez remplir tous les champs.";
        exit;
    }

    // si user existe déjà
    $requete = $pdo->prepare("SELECT account_id FROM accounts WHERE account_name = ?");
    $requete->execute([$nom]);
    if ($requete->fetch()) {
        echo "Nom d'utilisateur déjà utilisé.";
        exit;
    }

    $hash = password_hash($motDePasse, PASSWORD_DEFAULT);

    // crée new user
    $requete = $pdo->prepare("INSERT INTO accounts (account_name, account_passwd) VALUES (?, ?)");
    $requete->execute([$nom, $hash]);

    echo "Inscription réussie.";
}
?>
