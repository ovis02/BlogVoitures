<?php
session_start();
include '../connexion_database/configuration.php'; // Inclure le fichier de connexion

// Affichage des erreurs pour le débogage
error_reporting(error_level: E_ALL);
ini_set(option: 'display_errors', value: 1);

// Vérification des identifiants
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête SQL pour vérifier les identifiants dans la base de données
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare(query: $sql);
    $stmt->execute(params: [$username]);
    $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);

    // Vérification des résultats de la requête
    if ($user) {
        // Vérification du mot de passe
        if (password_verify(password: $password, hash: $user['password'])) {
            // Authentification réussie
            $_SESSION['username'] = $username; // Stocker le nom d'utilisateur dans la session
            header(header: "Location: ../interface.php"); // Rediriger vers l'interface
            exit();
        } else {
            // Mot de passe incorrect
            echo "<div class='alert alert-danger'>Mot de passe incorrect. Veuillez réessayer.</div>";
        }
    } else {
        // Utilisateur non trouvé
        echo "<div class='alert alert-danger'>Utilisateur non trouvé.</div>";
    }
} else {
    // Redirige vers la page de connexion si la méthode de requête n'est pas POST
    header(header: "Location: ../index.php");
    exit();
}
?>
