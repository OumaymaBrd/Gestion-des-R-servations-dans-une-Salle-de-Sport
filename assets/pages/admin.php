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

<form action="http://localhost/Salle_sport/assets/pages/Coach.php" method="get">
    <button type="submit">Revenir à l'interface de Coach</button>
</form>
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

    <!-- Statistique -->
    <?php
   $activity_count = 0;

   // SQL query to count all activities
   $sql_count = "SELECT COUNT(*) as total FROM activities";
   
   try {
       $stmt = $conn->prepare($sql_count);
       $stmt->execute();
       $result = $stmt->fetch(PDO::FETCH_ASSOC);
       $activity_count = $result['total'];
   } catch (PDOException $e) {
       // Log error but don't expose details to user
       error_log("Database error: " . $e->getMessage());
       $activity_count = 0; // Set default value if query fails
   }
   
   $display_count = isset($activity_count) ? htmlspecialchars((string)$activity_count) : '0';

//    

function getCount($conn, $sql) {
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    }
}
$sql_client_count = "SELECT COUNT(*) as total FROM tableau_authentifier WHERE Post = 'Client' AND Supprimer = 0";
$client_count = getCount($conn, $sql_client_count);

$sql_coach_count = "SELECT COUNT(*) as total FROM tableau_authentifier WHERE Post = 'Coach' AND Supprimer = 0";
$coach_count = getCount($conn, $sql_coach_count);

$total_inscriptions = 0;

$total_enrollments = 0;
$enrollment_query = "SELECT COUNT(*) as enrollment_total 
                     FROM tableau_activite 
                     WHERE Admit_activite = 'oui'";

try {
    $enrollment_statement = $conn->prepare($enrollment_query);
    $enrollment_statement->execute();
    $enrollment_result = $enrollment_statement->fetch(PDO::FETCH_ASSOC);
    $total_enrollments = $enrollment_result['enrollment_total'];
} catch (PDOException $database_error) {
    error_log("Database error occurred: " . $database_error->getMessage());
  
}

$display_enrollment_count = isset($total_enrollments) ? htmlspecialchars((string)$total_enrollments) : '0';
    ?>

    
    <label for="activity-count">Nombre d'activités : </label>
    <span id="activity-count"><?php echo $display_count; ?></span>

    <h2>Nombre de membres</h2>
    <p>Clients : <?php echo htmlspecialchars($client_count); ?></p>
    <p>Coachs : <?php echo htmlspecialchars($coach_count); ?></p>
    <label for="enrollment-count">Nombre total d'inscriptions approuvées : </label>
    <span id="enrollment-count"><?php echo $display_enrollment_count; ?></span>

    <script src="../script/script.js"></script>
</body>
</html>

