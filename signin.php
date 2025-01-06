<?php
session_start();
// Configuration de la base de données
$host = 'localhost';
$dbname = 'Formulaire';
$username = 'root'; // Remplacez par votre utilisateur MariaDB
$password = 'root';

try {
    // Connexion à MariaDB
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];

        // Rechercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo); // Lier le paramètre correctement
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && $password === $userData['mot_de_passe']) {
            // Stocker l'ID utilisateur dans la session
            $_SESSION['id_utilisateur'] = $userData['id'];
            // Utilisez mot_de_passe au lieu de password
            header('Location: Enquete.html');
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();

}
?>
