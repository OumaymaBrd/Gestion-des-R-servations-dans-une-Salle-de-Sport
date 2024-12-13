<?php
include 'connexion.php';
$message = '';
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

// Statistiques
$activity_count = 0;
$sql_count = "SELECT COUNT(*) as total FROM activities";
try {
    $stmt = $conn->prepare($sql_count);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $activity_count = $result['total'];
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $activity_count = 0;
}
$display_count = isset($activity_count) ? htmlspecialchars((string)$activity_count) : '0';

function getCount($conn, $sql) {
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if the result contains 'enrollment_total' or 'total' key
        if (isset($result['enrollment_total'])) {
            return $result['enrollment_total'];
        } elseif (isset($result['total'])) {
            return $result['total'];
        }
        // If neither key exists, return the first value
        return reset($result);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return 0;
    }
}

$sql_client_count = "SELECT COUNT(*) as total FROM tableau_authentifier WHERE Post = 'Client' AND Supprimer = 0";
$client_count = getCount($conn, $sql_client_count);

$sql_coach_count = "SELECT COUNT(*) as total FROM tableau_authentifier WHERE Post = 'Coach' AND Supprimer = 0";
$coach_count = getCount($conn, $sql_coach_count);

$enrollment_query = "SELECT COUNT(*) as enrollment_total 
                    FROM tableau_activite 
                    WHERE Admit_activite = 'oui'";
$total_enrollments = getCount($conn, $enrollment_query);

$display_enrollment_count = isset($total_enrollments) ? htmlspecialchars((string)$total_enrollments) : '0';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - FitnessPro Gym</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style_admin.css">
    <style>
        
    </style>

</head>
<body>
    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">
                <img src="../images/logo.png" alt="FitnessPro Gym Logo">
                <h1>FitnessPro Gym</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="active"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li><a href="#"><i class="fas fa-dumbbell"></i> Activités</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Membres</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Planning</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                <h2>Tableau de bord</h2>
                <form action="http://localhost/Salle_sport/assets/pages/Coach.php" method="get">
                    <button type="submit" class="btn btn-secondary">Revenir à l'interface de Coach</button>
                </form>
            </header>
            <?php if (!empty($message)): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <section class="stats">
                <div class="stat-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>Activités</h3>
                    <p><?php echo $display_count; ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user"></i>
                    <h3>Clients</h3>
                    <p><?php echo htmlspecialchars($client_count); ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-tie"></i>
                    <h3>Coachs</h3>
                    <p><?php echo htmlspecialchars($coach_count); ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Inscriptions approuvées</h3>
                    <p><?php echo $display_enrollment_count; ?></p>
                </div>
            </section>
            <section class="activities">
                <h2>Activités en attente d'acceptation</h2>
                <button id="toggleTable" class="btn btn-primary">Afficher/Cacher le tableau</button>
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
                                        <form method="post" class="action-buttons">
                                            <input type="hidden" name="id" value="<?php echo $activity['activity_id']; ?>">
                                            <button type="submit" name="valider" class="btn btn-success">Valider</button>
                                            <button type="submit" name="supprimer" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?');">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
    <script src="../script/script.js"></script>
</body>
</html>

