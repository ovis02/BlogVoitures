<?php
// Vérification de la soumission du formulaire et traitement du vote
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'configuration.php'; // Inclure le fichier de configuration de la base de données

    if (isset($_POST['vote'])) {
        $vote = $_POST['vote'];

        // Requête d'insertion du vote dans la base de données
        $sql = "INSERT INTO Votes (voiture_preferee) VALUES ('$vote')";

        if ($conn->query($sql) === TRUE) {
            // Requête pour obtenir le nombre de votes par voiture après l'insertion du nouveau vote
            $sqlCount = "SELECT voiture_preferee, COUNT(*) AS nombre_votes FROM Votes GROUP BY voiture_preferee";

            $result = $conn->query($sqlCount);

            if ($result->num_rows > 0) {
                $output = "<table>";
                $output .= "<tr><th>Voiture</th><th>Nombre de Votes</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $output .= "<tr><td>" . $row["voiture_preferee"] . "</td><td>" . $row["nombre_votes"] . "</td></tr>";
                }
                $output .= "</table>";
                echo $output; // Renvoi les résultats sous forme de tableau HTML
            } else {
                echo "Aucun vote enregistré.";
            }
        } else {
            echo "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }
}
?>
