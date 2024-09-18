<?php
$host = 'localhost'; // Adresse du serveur de base de données
$db = 'blogauto'; // Nom de la base de données
$user = 'root'; // Nom d'utilisateur
$pass = ''; // Mot de passe

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    // Création d'une instance PDO
    $pdo = new PDO($dsn, $user, $pass);
    // Défini le mode d'erreur PDO sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message et arrêter le script
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>

