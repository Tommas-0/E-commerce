<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "armpxjsa_Tommas";
$password = "7v}AVD;>%k";
$dbname = "armpxjsa_Gainy";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Définition du jeu de caractères UTF-8
$conn->set_charset("utf8mb4");
?>
