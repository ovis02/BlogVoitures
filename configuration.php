<?php
$servername = "localhost"; // Adresse du serveur MariaDB
$username = "root"; // Nom d'utilisateur
$password = ""; // Mot de passe
$dbname = "BlogAuto"; // Nom de la base de données

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>
