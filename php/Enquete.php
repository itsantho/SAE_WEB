<?php
namespace Projet\php;

use Projet\php\Exception\BddConnectException;

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: Identification.php');
    exit;
}

$id_utilisateur = $_SESSION['id_utilisateur'];

require_once 'BddConnect.php'; // Inclure le fichier contenant la classe BddConnect

// Création de l'instance BddConnect
$bdd = new BddConnect();

try {
    // Connexion à la base de données
    $conn = $bdd->connexion();

    // Vérification de la méthode POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des données du formulaire
        $region = $_POST['region'];
        $age = intval($_POST['age']);
        $habitation = $_POST['habitation'];
        $orientation_CDAPH = $_POST['orientation_CDAPH'];
        $lieu_choix = $_POST['lieu_choix'];
        $activite = $_POST['activite'];
        $qualite_vie = $_POST['qualite_vie'];
        $besoins_adherent = $_POST['besoins-adherent'];

        // Préparation de la requête SQL avec PDO
        $sql = "INSERT INTO reponses (idUtilisateur, region, age, habitation, orientation_CDAPH, lieu_choix, activite, qualite_vie, besoins_adherent)
                VALUES (:id_utilisateur, :region, :age, :habitation, :orientation_CDAPH, :lieu_choix, :activite, :qualite_vie, :besoins_adherent)";

        // Préparation de la requête
        $stmt = $conn->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':id_utilisateur', $id_utilisateur, \PDO::PARAM_INT);
        $stmt->bindParam(':region', $region, \PDO::PARAM_STR);
        $stmt->bindParam(':age', $age, \PDO::PARAM_INT);
        $stmt->bindParam(':habitation', $habitation, \PDO::PARAM_STR);
        $stmt->bindParam(':orientation_CDAPH', $orientation_CDAPH, \PDO::PARAM_STR);
        $stmt->bindParam(':lieu_choix', $lieu_choix, \PDO::PARAM_STR);
        $stmt->bindParam(':activite', $activite, \PDO::PARAM_STR);
        $stmt->bindParam(':qualite_vie', $qualite_vie, \PDO::PARAM_STR);
        $stmt->bindParam(':besoins_adherent', $besoins_adherent, \PDO::PARAM_STR);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Les réponses ont été enregistrées avec succès.";
        } else {
            echo "Erreur lors de l'enregistrement des réponses.";
        }
    }
} catch (BddConnectException $e) {
    // Gérer les erreurs de connexion
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
} catch (\PDOException $e) {
    // Gérer les erreurs de PDO
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
}
?>
