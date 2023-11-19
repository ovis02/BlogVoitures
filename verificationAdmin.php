<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = ""; // Mot de passe vide
$dbname = "BlogAuto";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification des informations d'identification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password']; // Récupération du mot de passe haché depuis la base de données

        // Vérification du mot de passe fourni par l'utilisateur avec le hachage stocké dans la base de données
        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            header("Location: interface.php");
            exit;
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
