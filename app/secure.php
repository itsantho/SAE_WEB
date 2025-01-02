<?php
namespace Projet\Signup;

session_start();

if (!isset($_SESSION['auth'])) {
    // Si l'utilisateur n'est pas authentifié, rediriger vers la page de connexion
    header("Location: signin.php");
    exit;
}

// Si l'utilisateur est authentifié, afficher la page sécurisée
echo "Bienvenue sur la page sécurisée !";

?>
