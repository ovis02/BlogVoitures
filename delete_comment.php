<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirige vers la page de connexion s'il n'est pas connecté
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BlogAuto";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Supprimer le commentaire
    $sql = "DELETE FROM Comments WHERE id = $comment_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: interface.php"); // Redirige vers l'interface d'administration
        exit;
    } else {
        echo "Erreur lors de la suppression du commentaire : " . $conn->error;
    }

    $conn->close();
} else {
    echo "ID du commentaire non fourni ou méthode incorrecte.";
}
?>
