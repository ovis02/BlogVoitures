<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogauto";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

$comment_id = $_POST['comment_id'];
$action = $_POST['action'];

if ($action == 'approve') {
    $sql = "UPDATE Comments SET validated = 1 WHERE id = ?";
} else if ($action == 'delete') {
    $sql = "DELETE FROM Comments WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $comment_id);

if ($stmt->execute()) {
    header("Location: admin_comments.php"); // Redirection après validation ou suppression
} else {
    echo "Erreur : " . $conn->error;
}

$stmt->close();
$conn->close();
?>
