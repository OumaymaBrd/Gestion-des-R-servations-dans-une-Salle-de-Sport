<?php
include 'connexion.php';

$message = '';

// Handle activity validation
if (isset($_POST['valider'])) {
    $id = $_POST['id'];
    try {
        $sql_valider = "UPDATE activities SET validation_admin = 0 WHERE activity_id = :id";
        $stmt_valider = $conn->prepare($sql_valider);
        $stmt_valider->bindParam(':id', $id);
        if ($stmt_valider->execute()) {
            $message = "Activité validée avec succès.";
        } else {
            $message = "Erreur lors de la validation de l'activité.";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

// Handle activity deletion
if (isset($_POST['supprimer'])) {
    $id = $_POST['id'];
    try {
        $sql_supprimer = "DELETE FROM activities WHERE activity_id = :id";
        $stmt_supprimer = $conn->prepare($sql_supprimer);
        $stmt_supprimer->bindParam(':id', $id);
        if ($stmt_supprimer->execute()) {
            $message = "Activité supprimée avec succès.";
        } else {
            $message = "Erreur lors de la suppression de l'activité.";
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

// Fetch pending activities
$sql_afficher = "SELECT * FROM activities WHERE validation_admin = 1";
$stmt_afficher = $conn->prepare($sql_afficher);
$stmt_afficher->execute();
$activities = $stmt_afficher->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - FitnessPro Gym</title>
    <link rel="stylesheet" href="../css/admin.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <h2>Les Activités en attente d'acceptation : </h2>

        <button onclick="toggleTable()">Afficher/Cacher le tableau</button>

        <div id="tableContainer" class="hidden">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Capacité</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Disponibilité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($activity['name']); ?></td>
                            <td><?php echo htmlspecialchars($activity['description']); ?></td>
                            <td><?php echo htmlspecialchars($activity['capacity']); ?></td>
                            <td><?php echo htmlspecialchars($activity['start_date']); ?></td>
                            <td><?php echo htmlspecialchars($activity['end_date']); ?></td>
                            <td><?php echo htmlspecialchars($activity['disponibility']); ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $activity['activity_id']; ?>">
                                    <button type="submit" name="valider">Valider</button>
                                </form>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $activity['activity_id']; ?>">
                                    <button type="submit" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    

    <script>
        function toggleTable() {
            var tableContainer = document.getElementById('tableContainer');
            if (tableContainer.classList.contains('hidden')) {
                tableContainer.classList.remove('hidden');
            } else {
                tableContainer.classList.add('hidden');
            }
        }
    </script>
</body>
</html>

