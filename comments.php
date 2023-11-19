<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BlogAuto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    // Nouvelle insertion avec un champ "validated" initialisé à 0 (non validé)
    $sql = "INSERT INTO Comments (name, email, comment, validated) VALUES ('$name', '$email', '$comment', 0)";

    if ($conn->query($sql) === TRUE) {
        echo "Le commentaire a été ajouté avec succès !";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
