<?php

if (!session_id()) {
    session_start();
}

require_once 'BddConnect.php';
use Projet\Signup\BddConnect;
use Projet\Signup\Exception\BddConnectException;

// Fonction pour valider les champs
function validateForm($data) {
    $errors = [];

    if (empty($data['nom'])) $errors[] = "Le champ Nom est obligatoire.";
    if (empty($data['prenom'])) $errors[] = "Le champ Prénom est obligatoire.";
    if (empty($data['gender'])) $errors[] = "Le champ Civilité est obligatoire.";
    if (empty($data['dob'])) $errors[] = "La date de naissance est obligatoire.";
    if (empty($data['adresse'])) $errors[] = "Le champ Adresse est obligatoire.";
    if (empty($data['CP'])) $errors[] = "Le champ Code postal est obligatoire.";
    if (empty($data['city'])) $errors[] = "Le champ Ville est obligatoire.";
    if (empty($data['pays'])) $errors[] = "Le champ Pays est obligatoire.";
    if (empty($data['numtel'])) $errors[] = "Le champ Téléphone est obligatoire.";
    if (empty($data['mail'])) {
        $errors[] = "Le champ Email est obligatoire.";
    } elseif (!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Le format de l'email est invalide.";
    }
    if (empty($data['pseudo'])) $errors[] = "Le champ Pseudo est obligatoire.";
    if (empty($data['mdp'])) $errors[] = "Le champ Mot de passe est obligatoire.";
    if ($data['mdp'] !== $data['cmdp']) $errors[] = "Les mots de passe ne correspondent pas.";

    return $errors;
}

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = array_map('htmlspecialchars', array_map('trim', $_POST));

    // Validation des données
    $errors = validateForm($formData);

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    }

    // Hashage du mot de passe
    $hashed_password = password_hash($formData['mdp'], PASSWORD_DEFAULT);

    try {
        // Initialisation de la connexion à la base de données
        $bdd = new BddConnect();
        $pdo = $bdd->connexion();

        // Début de la transaction
        $pdo->beginTransaction();

        // Insertion dans la table utilisateurs
        $sqlUtilisateur = "INSERT INTO utilisateurs (nom, prenom, date_naissance, adresse, adresse2, code_postal, ville, pays, telephone, email, profession, activite_actuelle, maladie, annee_souffrance, autres_maladies, connaissance_association)
                           VALUES (:nom, :prenom, :dob, :adresse, :adresse2, :CP, :city, :pays, :numtel, :mail, :job, :activity, :maladie, :annee, :autres, :connaissance)";
        $stmtUtilisateur = $pdo->prepare($sqlUtilisateur);
        $stmtUtilisateur->execute([
            ':nom' => $formData['nom'],
            ':prenom' => $formData['prenom'],
            ':dob' => $formData['dob'],
            ':adresse' => $formData['adresse'],
            ':adresse2' => $formData['adresse2'] ?? null,
            ':CP' => $formData['CP'],
            ':city' => $formData['city'],
            ':pays' => $formData['pays'],
            ':numtel' => $formData['numtel'],
            ':mail' => $formData['mail'],
            ':job' => $formData['job'] ?? null,
            ':activity' => $formData['activity'] ?? null,
            ':maladie' => $formData['maladie'] ?? null,
            ':annee' => $formData['annee'] ?? null,
            ':autres' => $formData['autres'] ?? null,
            ':connaissance' => $formData['connaissance']
        ]);

        // Récupération de l'ID de l'utilisateur nouvellement inséré
        $userId = $pdo->lastInsertId();

        // Insertion dans la table connexion (pseudo et mot de passe)
        $sqlConnexion = "INSERT INTO connexion (utilisateur_id, pseudo, mot_de_passe)
                         VALUES (:user_id, :pseudo, :mdp)";
        $stmtConnexion = $pdo->prepare($sqlConnexion);
        $stmtConnexion->execute([
            ':user_id' => $userId,
            ':pseudo' => $formData['pseudo'],
            ':mdp' => $hashed_password,
        ]);

        // Insertion dans la table consentements
        $sqlConsentement = "INSERT INTO consentements (utilisateur_id)
                            VALUES (:user_id)";
        $stmtConsentement = $pdo->prepare($sqlConsentement);
        $stmtConsentement->execute([
            ':user_id' => $userId,
        ]);

        // Validation de la transaction
        $pdo->commit();

        echo json_encode(['status' => 'success', 'redirect' => 'identification.html']);
    } catch (BddConnectException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    } catch (\PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'insertion : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requête non valide.']);
}
?>
