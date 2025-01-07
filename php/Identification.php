<?php
session_start();

use Projet\php\BddConnect;
use Projet\php\Exception\BddConnectException;

// Inclure le fichier contenant la classe BddConnect
require_once 'BddConnect.php';

try {
    // Création de l'instance BddConnect et connexion à la base de données
    $bdd = new BddConnect();
    $pdo = $bdd->connexion(); // Utilisation de la méthode connexion pour obtenir l'objet PDO

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];

        // Rechercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR); // Lier le paramètre correctement
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['mot_de_passe'])) {
            // Stocker l'ID utilisateur dans la session
            $_SESSION['id_utilisateur'] = $userData['id'];
            // Rediriger vers la page de l'enquête
            header('Location: ../Enquete.html');
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
} catch (BddConnectException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
} catch (PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>
