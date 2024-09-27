<?php
include 'cookie.php'; // Inclu le fichier contenant les fonctions pour les cookies

// Vérification de la soumission du formulaire et traitement du vote
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../connexion_database/configuration.php'; // Inclu le fichier de configuration de la base de données

    if (isset($_POST['vote'])) {
        // Vérifie si un cookie existe pour empêcher un vote multiple
        if (!getVoteCookie()) {
            $vote = $_POST['vote'];

            // Requête d'insertion du vote dans la base de données
            $sql = "INSERT INTO votes (voiture_preferee) VALUES (:vote)"; // Utilisation de la syntaxe préparée

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':vote', $vote);

            if ($stmt->execute()) {
                // Enregistrer le vote dans un cookie pour éviter le vote multiple
                createVoteCookie($vote);

                // Requête pour obtenir le nombre de votes par voiture après l'insertion du nouveau vote
                $sqlCount = "SELECT voiture_preferee, COUNT(*) AS nombre_votes FROM votes GROUP BY voiture_preferee";
                $result = $pdo->query($sqlCount);

                if ($result->rowCount() > 0) {
                    $output = "<table>";
                    $output .= "<tr><th>Voiture</th><th>Nombre de Votes</th></tr>";
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $output .= "<tr><td>" . htmlspecialchars($row["voiture_preferee"]) . "</td><td>" . htmlspecialchars($row["nombre_votes"]) . "</td></tr>";
                    }
                    $output .= "</table>";
                    echo $output; // Renvoi les résultats sous forme de tableau HTML
                } else {
                    echo "Aucun vote enregistré.";
                }
            } else {
                echo "Erreur lors de l'exécution de la requête.";
            }
        } else {
            echo "Vous avez déjà voté !"; // Message indiquant que l'utilisateur a déjà voté
        }
    }
}
?>
