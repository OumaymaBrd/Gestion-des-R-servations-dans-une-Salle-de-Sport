<?php
    include 'connexion.php';

     $sql = "SELECT * FROM activities where validation_admin='0' ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessPro Gym - Activités</title>
    <link rel="stylesheet" href="../css/style_activite.css">
</head>
<body>
    <header>
        <h1>FitnessPro Gym</h1>
        <nav>
            <ul>
                <li><a href="../../index.php">Accueil</a></li>
                <li><a href="../pages/contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <?php
$activity_id = $_GET['id'] ?? null;
$matricule = $_GET['matricule'] ?? null;
function isReserved($conn, $activity_id, $matricule) {
    $sql = "SELECT * FROM reservations WHERE member_id = :member_id AND matricule = :matricule AND status = 'Confirmed'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':member_id', $activity_id, PDO::PARAM_INT);
    $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

if ($activities) {
    echo "<div class='container'>
        <h2>Nos Activités</h2>
        <div class='activities'>";

    foreach ($activities as $activity) {
        $isReserved = isReserved($conn, $activity['activity_id'], $matricule);
        $buttonText = $isReserved ? 'Non réserver' : 'Réserver';
        $formAction = $isReserved ? 'cancel' : 'reserve';

        echo "<div class='activity-card'>
            <h3>" . htmlspecialchars($activity['name']) . "</h3>
            <p>" . htmlspecialchars($activity['description']) . "</p>

            <form method='POST' action='' class='reservation-form'>
                <input type='hidden' name='activity_id' value='" . htmlspecialchars($activity['activity_id']) . "'>
                <input type='hidden' name='activity_name' value='" . htmlspecialchars($activity['name']) . "'>
                <input type='hidden' name='matricule' value='" . htmlspecialchars($matricule) . "'>
                <input type='hidden' name='action' value='" . $formAction . "'>
                <button type='submit' name='submit' class='btn'>" . $buttonText . "</button>
            </form>
        </div>";
    }

    echo "</div>
    </div>";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $activity_id = $_POST['activity_id'] ?? '';
        $activity_name = $_POST['activity_name'] ?? '';
        $matricule = $_POST['matricule'] ?? '';
        $action = $_POST['action'] ?? '';

        if ($activity_id && $activity_name && $matricule && $action) {
            try {
                if ($action === 'reserve') {
                    $sql = "INSERT INTO reservations (member_id, matricule, activity, reservation_date, status) 
                            VALUES (:member_id, :matricule, :activity, NOW(), 'Confirmed')";
                } else {
                    $sql = "UPDATE reservations SET status = 'Cancelled' 
                            WHERE member_id = :member_id AND matricule = :matricule AND activity = :activity";
                }

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':member_id', $activity_id, PDO::PARAM_INT);
                $stmt->bindParam(':matricule', $matricule, PDO::PARAM_STR);
                $stmt->bindParam(':activity', $activity_name, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $message = $action === 'reserve' 
                        ? "Votre réservation pour l'activité '" . htmlspecialchars($activity_name) . "' a été confirmée." 
                        : "Votre réservation pour l'activité '" . htmlspecialchars($activity_name) . "' a été annulée.";
                    echo "<p class='success'>" . $message . "</p>";
                } else {
                    echo "<p class='error'>Une erreur est survenue lors de l'opération. Veuillez réessayer.</p>";
                }
            } catch (PDOException $e) {
                echo "<p class='error'>Erreur de base de données: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        } else {
            echo "<p class='error'>Informations incomplètes. Veuillez réessayer.</p>";
        }
    }
} else {
    echo "<p>Aucune activité trouvée.</p>";
}
?>

<script src="../script/client.js"></script>
    <footer>
        <p>&copy; 2024 FitnessPro Gym. Tous droits réservés.</p>
    </footer>
</body>
</html>