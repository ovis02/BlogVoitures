<?php
$host = 'q2gen47hi68k1yrb.chr7pe7iynqr.eu-west-1.rds.amazonaws.com'; // Host distant
$db = 'ofdf6vm86czeemf4'; // Nom de la base de données
$user = 'cfwgxbjq7k4lk44u'; // Nom d'utilisateur
$pass = 'z4ov5t4efw1ej0k2'; // Mot de passe

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>