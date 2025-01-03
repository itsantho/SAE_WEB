<?php
$host = 'localhost';
$username = 'root'; // À modifier avec votre utilisateur MariaDB
$password = 'root'; // À modifier avec votre mot de passe MariaDB
$dbname = 'Formulaire';

$conn = new mysqli($host, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupération des données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $region = $conn->real_escape_string($_POST['region']);
    $age = intval($_POST['age']);
    $habitation = $conn->real_escape_string($_POST['habitation']);
    $orientation_CDAPH = $conn->real_escape_string($_POST['orientation_CDAPH']);
    $lieu_choix = $conn->real_escape_string($_POST['lieu_choix']);
    $activite = $conn->real_escape_string($_POST['activite']);
    $qualite_vie = $conn->real_escape_string($_POST['qualite_vie']);
    $besoins_adherent = $conn->real_escape_string($_POST['besoins-adherent']);

    // Préparation et exécution de la requête SQL
    $sql = "INSERT INTO reponses (region, age, habitation, orientation_CDAPH, lieu_choix, activite, qualite_vie, besoins_adherent)
            VALUES ('$region', $age, '$habitation', '$orientation_CDAPH', '$lieu_choix', '$activite', '$qualite_vie', '$besoins_adherent')";

    if ($conn->query($sql) === TRUE) {
        echo "Les réponses ont été enregistrées avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Fermeture de la connexion
$conn->close();
?>
