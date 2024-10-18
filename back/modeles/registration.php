<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once '../controllers/registrationController.php';

// Définition de la classe Registration
class Registration
{
    private $connexion; // Stocke la connexion à la base de données
    private $table = "registrations"; // Nom de la table dans la base de données

    // Propriétés de l'enregistrement
    public $id;
    public $firstname;
    public $lastname;
    public $type;
    public $email;
    public $birth;
    public $phone;
    public $country;
    public $questions;
    public $IP;
    public $createdAt;
    public $updatedAt;
    public $counter;

    // Constructeur prenant en paramètre la connexion à la base de données
    public function __construct($db)
    {
        $this->connexion = $db; // Initialise la connexion à la base de données
    }

    // Méthode pour vérifier si l'utilisateur a déjà fait une inscription dans les 24 heures
    public function checkMultipleRegistrations()
    {
        $ip = $this->IP;
        $sql = "SELECT COUNT(*) AS count FROM " . $this->table . " WHERE IP = :IP AND updatedAt >= NOW() - INTERVAL 1 DAY";
        $query = $this->connexion->prepare($sql);
        $query->bindValue(":IP", $ip);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'];
        return $count > 0;
    }
    // Méthode pour ajouter un enregistrement en vérifiant les inscriptions multiples dans les 24 heures
    public function registration()
    {
        // Vérification des inscriptions multiples dans les 24 heures
        if ($this->checkMultipleRegistrations()) {
            return false;
        }
    
        // on s'assure que createdAt correspond à la première date d'inscription
        $this->setCreatedAtToFirstRegistration();
    
        // on exécute d'abord la requête pour mettre à jour les données si l'utilisateur est déjà enregistré
        $sql = "UPDATE " . $this->table . " SET counter = counter + 1, firstname = :firstname, lastname = :lastname, type = :type, email = :email, birth = :birth, phone = :phone, country = :country, questions = :questions, updatedAt = NOW() WHERE IP = :IP";
        $query = $this->connexion->prepare($sql);
        $query->bindValue(":firstname", $this->firstname);
        $query->bindValue(":lastname", $this->lastname);
        $query->bindValue(":type", $this->type);
        $query->bindValue(":email", $this->email);
        $query->bindValue(":birth", $this->birth);
        $query->bindValue(":phone", $this->phone);
        $query->bindValue(":country", $this->country);
        $query->bindValue(":questions", $this->questions);
        $query->bindValue(":IP", $this->IP);
        $query->execute();
    
        // on vérifie combien de lignes ont été affectées par la mise à jour
        $rowsAffected = $query->rowCount();
    
        if ($rowsAffected == 0) {
            // Si aucune ligne n'a été affectée (c'est-à-dire que l'utilisateur n'est pas déjà enregistré),
            // on exécute la requête d'insertion pour enregistrer de nouvelles données
            $sql = "INSERT INTO " . $this->table . " (firstname, lastname, type, email, birth, phone, country, questions, IP, createdAt, updatedAt, counter) VALUES (:firstname, :lastname, :type, :email, :birth, :phone, :country, :questions, :IP, NOW(), NOW(), 1)";
            $query = $this->connexion->prepare($sql);
            // Liaison des valeurs avec les paramètres de la requête
            $query->bindValue(":firstname", $this->firstname);
            $query->bindValue(":lastname", $this->lastname);
            $query->bindValue(":type", $this->type);
            $query->bindValue(":email", $this->email);
            $query->bindValue(":birth", $this->birth);
            $query->bindValue(":phone", $this->phone);
            $query->bindValue(":country", $this->country);
            $query->bindValue(":questions", $this->questions);
            $query->bindValue(":IP", $this->IP);
            // Exécution de la requête d'insertion
            $query->execute();
        }
    
        return true;
    }

    // Méthode pour définir createdAt à la première date d'inscription
    private function setCreatedAtToFirstRegistration()
    {
        // Requête SQL pour obtenir la première date d'inscription
        $sql = "SELECT MIN(createdAt) AS firstRegistration FROM " . $this->table;
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // Mettre à jour la valeur de createdAt avec la première date d'inscription
        $this->createdAt = $result['firstRegistration'];
    }
    // Méthode pour vérifier si l'utilisateur est mineur
    public function isMinor()
    {
        $birthdate = new DateTime($this->birth);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;
        if ($age < 18) {
            return "Vous devez être majeur pour vous inscrire.";
        }
        return false; 
    }
    // Méthode pour valider les données avant l'inscription
    public function validateRegistrationData()
    {

        // Vérification de chaque champ
        if (empty($this->firstname) || empty($this->lastname) || empty($this->type) || empty($this->email) || empty($this->birth) || empty($this->phone) || empty($this->country) || empty($this->questions)) {
            return "Tous les champs doivent être remplis.";
        }
        // Vérification du format de l'email
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "L'adresse e-mail n'est pas valide.";
        }
        // Vérification si le numéro de téléphone est composé exactement de 10 chiffres
        if (!preg_match('/^\d{10}$/', $this->phone)) {
            return "Le numéro de téléphone doit comporter exactement 10 chiffres.";
        }
        return true; 

    }

}


?>
