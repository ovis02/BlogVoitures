<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commentaires</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="interface.css">
</head>

<body>
    <div class="container-fluid py-5">
        <div class="d-flex justify-content-center align-items-center mb-4">
            <h1 class="text-uppercase text-orange text-center">Système de gestion des commentaires</h1>
            
            <!-- Bouton de déconnexion -->
            <form method="POST" action="connexion_database/logout.php" class="position-absolute top-0 end-0 m-3">
                <button type="submit" class="btn btn-danger">Déconnexion</button>
            </form>
        </div>

        <p class="text-muted text-center">Interface Blog Automobile</p>

        <?php
        include 'connexion_database/configuration.php'; // Fichier de connexion à la base de données

        try {
            // Récupération des commentaires non validés
            $sql = "SELECT id, name, email, comment, created_at FROM comments WHERE validated = 0";
            $stmt = $pdo->query($sql);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>La connexion a échoué : " . htmlspecialchars($e->getMessage()) . "</div>";
            exit;
        }
        ?>

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
                    if ($comments) {
                        foreach ($comments as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['comment']) . "</td>";
                            echo "<td>";
                            echo "<form method='POST' action='comments/validate_comment.php' style='display:inline;'>"; // Corrigez le chemin si nécessaire
                            echo "<input type='hidden' name='comment_id' value='" . $row['id'] . "' />";
                            echo "<button class='btn btn-sm btn-success' name='action' value='approve' aria-label='Valider le commentaire'>Valider</button>";
                            echo "<button class='btn btn-sm btn-danger' name='action' value='delete' aria-label='Supprimer le commentaire'>Supprimer</button>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
