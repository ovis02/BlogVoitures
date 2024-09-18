<?php
include 'configuration.php'; // Inclure le fichier de configuration de la base de données

// Requête pour obtenir le nombre de votes par voiture
$sql = "SELECT voiture_preferee, COUNT(*) AS nombre_votes FROM Votes GROUP BY voiture_preferee";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Voiture</th><th>Nombre de Votes</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["voiture_preferee"] . "</td><td>" . $row["nombre_votes"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "Aucun vote enregistré.";
}

// Fermer la connexion à la base de données
$conn->close();
?>

