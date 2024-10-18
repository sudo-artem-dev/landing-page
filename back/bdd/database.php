<?php

class Database
{
    // Propriétés de la base de données
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    public $connexion;

    public function __construct()
    {
        // On récupère les informations de la base de données
        $this->host = "localhost";
        $this->db_name = "bdd_landing_page";
        $this->username = "root";
        $this->password = "";
    }

    public function getConnection()
    {
        // On commence par fermer la connexion si elle existait
        $this->connexion = null;
    
        // On essaie de se connecter
        try {
            // On instancie la connexion à mysql
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name;
            $this->connexion = new PDO($dsn, $this->username, $this->password);
    
            // On force les transactions en UTF-8
            $this->connexion->exec("set names utf8");
        } catch (PDOException $exception) {
            // On récupère les erreurs éventuelles puis on les affiche
            echo "Erreur de connexion : " . $exception->getMessage();
        }
    
        // On retourne la connexion
        return $this->connexion;
    }
    
}

