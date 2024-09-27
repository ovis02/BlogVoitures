<?php
session_start(); // Assurez-vous que la session est démarrée
include '../connexion_database/configuration.php'; // Inclure le fichier de connexion

// Vérification des données POST
if (isset($_POST['comment_id']) && isset($_POST['action'])) {
    $comment_id = $_POST['comment_id'];
    $action = $_POST['action'];

    try {
        // Préparation de la requête en fonction de l'action
        if ($action == 'approve') {
            $sql = "UPDATE comments SET validated = 1 WHERE id = ?";
        } elseif ($action == 'delete') {
            $sql = "DELETE FROM comments WHERE id = ?";
        } else {
            die("Action non reconnue.");
        }

        // Préparation et exécution de la requête
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$comment_id]);

        // Redirection vers l'interface après l'action
        header("Location: ../interface.php");
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
    }
} else {
    die("Données invalides.");
}
?>
