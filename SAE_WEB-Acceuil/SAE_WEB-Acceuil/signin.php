<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'Formulaire';
$username = 'root'; // Remplacez par votre utilisateur MariaDB
$password = 'root'; // Laissez vide si vous utilisez XAMPP ou WAMP par défaut

try {
    // Connexion à MariaDB
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Traitement du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pseudo = $_POST['pseudo']; // Utiliser $pseudo au lieu de $user
        $password = $_POST['password'];

        // Rechercher l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo); // Lier le paramètre correctement
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && $password === $userData['mot_de_passe']) { // Utilisez mot_de_passe au lieu de password
            echo "Connexion réussie. Bienvenue, " . htmlspecialchars($userData['pseudo'])  . "!"; // Afficher pseudo et pas username
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
            echo $pseudo . $password;
            echo $userData['mot_de_passe'];
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();

}
?>
