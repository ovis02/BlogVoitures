<?php
// Indique que la réponse sera au format JSON
header('Content-Type: application/json');

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclut le fichier de connexion à la base de données
    include_once "connexion_database/configuration.php"; // Ajustez le chemin si nécessaire

    // Récupère et assainit les données soumises depuis le formulaire
    $name = trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $comment = trim(htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8'));

    // Vérifie que les données sont valides
    if ($name && $email && $comment) {
        // Prépare la requête SQL pour insérer le commentaire dans la base de données
        $sql = "INSERT INTO comments (name, email, comment) VALUES (?, ?, ?)";

        // Prépare la requête
        $stmt = $pdo->prepare($sql);

          // Exécute la requête en liant les valeurs des paramètres
        $stmt->execute([$name, $email, $comment]);

        // Répondre avec un message de succès
        echo json_encode(['status' => 'success', 'message' => 'Votre avis a été soumis avec succès.']);
    } else {
        // Répondre avec un message d'erreur
        echo json_encode(['status' => 'error', 'message' => 'Données invalides. Veuillez réessayer.']);
    }
} else {
    // Répond avec une erreur si la requête n'est pas de type POST
    echo json_encode(['status' => 'error', 'message' => 'Requête non autorisée.']);
}
?>