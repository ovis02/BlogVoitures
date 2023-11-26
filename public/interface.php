<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Interface d'administration des commentaires</title>
  <link rel="stylesheet" href="" />
</head>
<body>
  <h2>Commentaires en attente de validation</h2>
  <div class="comments-list">
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "BlogAuto";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
      die("La connexion a échoué : " . $conn->connect_error);
    }

    $sql = "SELECT * FROM Comments WHERE validated = 0";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<div class=\"comment\">";
        echo "<p><strong>Nom :</strong> " . $row['name'] . "</p>";
        echo "<p><strong>Email :</strong> " . $row['email'] . "</p>";
        echo "<p><strong>Commentaire :</strong> " . $row['comment'] . "</p>";
        echo "<form action=\"validate_comment.php\" method=\"POST\">";
        echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "'>";
        echo "<input type=\"submit\" name=\"validate\" value=\"Valider\" />";
        echo "</form>";
        
            // Ajout du bouton "Supprimer" avec un formulaire distinct pour chaque commentaire
        echo "<form action=\"delete_comment.php\" method=\"POST\">";
        echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "'>";
        echo "<button type=\"submit\" name=\"delete\">Supprimer</button>";
        echo "</form>";
        echo "</div>";
      }
    } else {
      echo "Aucun commentaire en attente.";
    }

    $conn->close();
    ?>
  </div>
</body>
</html>
