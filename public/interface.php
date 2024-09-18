<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commentaires - Interface Futuriste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="interface.css">
</head>

<body>
    <div class="container-fluid py-5">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <!-- Titre centré -->
            <h1 class="text-uppercase text-orange text-center">Système de gestion des commentaires</h1>
            
            <!-- Bouton de déconnexion futuriste -->
            <form method="POST" action="connexion_database/logout.php" class="position-absolute top-0 end-0 m-3">
                <button type="submit" class="btn btn-design">Déconnexion</button>
            </form>
        </div>

        <p class="text-muted text-center">Interface Futuriste avec des éléments de gestion extraordinaires</p>

        <!-- Connexion à la base de données -->
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "blogauto";

        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("La connexion a échoué : " . $conn->connect_error);
        }

        // Récupération des commentaires non validés
        $sql = "SELECT id, name, email, comment, created_at FROM Comments WHERE validated = 0";
        $result = $conn->query($sql);
        ?>

        <!-- Tableau de gestion des commentaires -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered comment-table">
                <thead>
                    <tr class="table-header">
                        <th scope="col">Pseudo</th>
                        <th scope="col">Email</th>
                        <th scope="col">Date</th>
                        <th scope="col">Commentaire</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='comments/validate_comment.php' style='display:inline;'>";
                            echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "' />";
                            echo "<button class='btn btn-sm btn-validate' name='action' value='approve'>Valider</button>";
                            echo "<button class='btn btn-sm btn-delete' name='action' value='delete'>Supprimer</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Aucun commentaire en attente.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
