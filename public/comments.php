<?php
// Indique que la réponse sera au format JSON
header('Content-Type: application/json');

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclut le fichier de connexion à la base de données
    include_once "connexion_database/configuration.php"; // Ajustez le chemin si nécessaire

    // Vérifie la connexion
    if (!$pdo) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion à la base de données.']);
        exit;
    }

    // Récupère et assainit les données soumises depuis le formulaire
    $name = trim(htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'));
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $comment = trim(htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8'));

    // Vérifie que les données sont valides
    if (!$name) {
        echo json_encode(['status' => 'error', 'message' => 'Nom invalide.']);
        exit;
    }
    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'Email invalide.']);
        exit;
    }
    if (!$comment) {
        echo json_encode(['status' => 'error', 'message' => 'Commentaire invalide.']);
        exit;
    }

    // Prépare la requête SQL pour insérer le commentaire dans la base de données
    $sql = "INSERT INTO comments (name, email, comment) VALUES (?, ?, ?)";

    // Prépare la requête
    $stmt = $pdo->prepare($sql);

    // Exécute la requête en liant les valeurs des paramètres
    try {
        $stmt->execute([$name, $email, $comment]);
        echo json_encode(['status' => 'success', 'message' => 'Votre avis a été soumis avec succès.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'insertion dans la base de données : ' . $e->getMessage()]);
    }
} else {
    // Répond avec une erreur si la requête n'est pas de type POST
    echo json_encode(['status' => 'error', 'message' => 'Requête non autorisée.']);
}
?>
