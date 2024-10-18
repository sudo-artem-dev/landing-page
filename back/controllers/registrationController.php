<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérification que le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclusion du fichier de connexion à la base de données
    include_once '../bdd/database.php';

    // Instanciation de la classe Database
    $database = new Database();

    // Récupération de la connexion à la base de données
    $db = $database->getConnection();

    // Inclure le fichier de la classe Registration
    include_once '../modeles/registration.php';

    // Instanciation de l'objet Registration avec la connexion à la base de données
    $registration = new Registration($db);

    // Assignation des valeurs du formulaire aux propriétés de l'objet Registration
    $registration->firstname = $_POST['prenom'];
    $registration->lastname = $_POST['nom'];
    
    // Vérifier si le genre a été sélectionné et attribuer la valeur appropriée
    if (isset($_POST['genre']) && ($_POST['genre'] == 'Homme' || $_POST['genre'] == 'Femme')) {
        $registration->type = $_POST['genre'];
    } 
    
    $registration->email = $_POST['email'];
    $registration->birth = $_POST['date'];
    $registration->phone = $_POST['telephone'];
    $registration->country = $_POST['pays'];
    $registration->questions = $_POST['question'];
    $registration->IP = $_SERVER['REMOTE_ADDR']; // Obtenir l'adresse IP du client
    $registration->createdAt = date('Y-m-d H:i:s'); // Date et heure actuelles
    $registration->updatedAt = date('Y-m-d H:i:s'); // Date et heure actuelles
    $registration->counter = 0; // Initialisation du compteur

    // Vérification si tous les champs sont remplis et si le numéro de téléphone est correct
    $validationError = $registration->validateRegistrationData();
    if ($validationError !== true) {
        // Renvoyer le message d'erreur sous forme de JSON
        echo json_encode(["error" => $validationError]);
        exit;
    }  else {
        // Vérification du format de l'email
        if (!filter_var($registration->email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["error" => "L'adresse e-mail n'est pas valide."]);
            exit;
        }
        
        // on vérifie si l'user est mineur avant d'ajouter l'enregistrement
        $minorError = $registration->isMinor();
        if ($minorError) {
            // Renvoyer le message d'erreur si l'user est mineur
            echo json_encode(["error" => $minorError]);
            exit;
        } else {
        // on vérifie  si l'user a déjà fait une inscription dans la même heure
        if ($registration->checkMultipleRegistrations()) {
            // Renvoyer le message d'erreur si l'user a déjà fait une inscription dans la même heure
            echo json_encode(["error" => "Vous avez déjà effectué une inscription dans la même heure."]);
            exit;
        } else {
                // Ajout de l'enregistrement à la base de données
                if ($registration->registration()) {
                    // Renvoyer un message de succès
                    echo json_encode(["success" => "Enregistrement ajouté avec succès."]);
                    exit;
                } else {
                    // Renvoyer un message d'erreur si une erreur s'est produite lors de l'ajout de l'enregistrement
                    echo json_encode(["error" => "Une erreur s'est produite lors de l'ajout de l'enregistrement."]);
                    exit;
                }
            }
        }
    }
}
?>
