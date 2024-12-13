<?php
include 'connexion.php';

// Fetch activities
$sql_activities = "SELECT name FROM activities";
$stmt_activities = $conn->prepare($sql_activities);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

// Fetch coaches
$sql_coaches = "SELECT nomComplet_coach FROM tableau_authentifier WHERE Post='Coach'";
$stmt_coaches = $conn->prepare($sql_coaches);
$stmt_coaches->execute();
$coaches = $stmt_coaches->fetchAll(PDO::FETCH_ASSOC);

$message = '';

if(isset($_POST['ajouter'])){
    $matricule = $_POST['Matricule'] ?? '';
    $nom_complet_membre = $_POST['nom_complet_membre'] ?? '';
    $activity = $_POST['activity'] ?? '';
    $coach = $_POST['coach'] ?? '';
    $post = 'Client'; 

    if (!empty($matricule) && !empty($nom_complet_membre) && !empty($activity) && !empty($coach)) {
        try {
            $conn->beginTransaction();

            // Insert into tableau_authentifier
            $sql1 = "INSERT INTO tableau_authentifier (Matricule, nom_complet_membre, Post, nomComplet_coach) 
                    VALUES (:Matricule, :nom_complet_membre, :Post, :coach)";
            
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':Matricule', $matricule);
            $stmt1->bindParam(':nom_complet_membre', $nom_complet_membre);
            $stmt1->bindParam(':Post', $post);
            $stmt1->bindParam(':coach', $coach);
            $stmt1->execute();

            // Insert into reservations
            $sql2 = "INSERT INTO reservations (Matricule, activity, Post, reservation_date, status) 
                    VALUES (:Matricule, :activity, :Post, NOW(), 'Confirmed')";

            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(':Matricule', $matricule);
            $stmt2->bindParam(':activity', $activity);
            $stmt2->bindParam(':Post', $post);
            $stmt2->execute();

            $conn->commit();
            $message = "Membre ajouté avec succès et réservation créée.";
        } catch (PDOException $e) {
            $conn->rollBack();
            $message = "Erreur lors de l'ajout du membre et de la réservation: " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

// Fetch existing reservations
$sql_reservations = "SELECT r.id, r.Matricule, r.activity, r.status, t.nom_complet_membre, t.nomComplet_coach 
                     FROM reservations r 
                     JOIN tableau_authentifier t ON r.Matricule = t.Matricule";
$stmt_reservations = $conn->prepare($sql_reservations);
$stmt_reservations->execute();
$reservations = $stmt_reservations->fetchAll(PDO::FETCH_ASSOC);

// Handle modification and deletion
if(isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $new_status = $_POST['new_status'];
    $sql_update = "UPDATE reservations SET status = :status WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':status', $new_status);
    $stmt_update->bindParam(':id', $id);
    if($stmt_update->execute()) {
        $message = "Réservation mise à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour de la réservation.";
    }
}

if(isset($_POST['supprimer'])) {
    $id = $_POST['id'];
    $sql_delete = "DELETE FROM reservations WHERE id = :id";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $id);
    if($stmt_delete->execute()) {
        $message = "Réservation supprimée avec succès.";
    } else {
        $message = "Erreur lors de la suppression de la réservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des membres et des activités</title>
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
    </style>
</head>
<body>
    <h1>Formulaire pour ajouter des membres à une activité</h1>
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="activity">Nom activité :</label>
        <select name="activity" id="activity" required>
            <option value="">Choisissez une activité</option>
            <?php foreach ($activities as $activity): ?>
                <option value="<?php echo htmlspecialchars($activity['name']); ?>">
                    <?php echo htmlspecialchars($activity['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="coach">Nom coach :</label>
        <select name="coach" id="coach" required>
            <option value="">Choisissez un coach</option>
            <?php foreach ($coaches as $coach): ?>
                <option value="<?php echo htmlspecialchars($coach['nomComplet_coach']); ?>">
                    <?php echo htmlspecialchars($coach['nomComplet_coach']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="Matricule">Matricule :</label>
        <input type="text" name="Matricule" id="Matricule" required>

        <label for="nom_complet_membre">Nom complet :</label>
        <input type="text" name="nom_complet_membre" id="nom_complet_membre" required>

        <label for="">phone number</label> <input type="tel" name="tel">
        <label for="">email</label> <input type="email" name="email">

        <button type="submit" name="ajouter">Ajouter</button>
    </form>

    <h2>Réservations existantes</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Matricule</th>
            <th>Nom complet</th>
            <th>Activité</th>
            <th>Coach</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($reservations as $reservation): ?>
            <tr>
                <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                <td><?php echo htmlspecialchars($reservation['Matricule']); ?></td>
                <td><?php echo htmlspecialchars($reservation['nom_complet_membre']); ?></td>
                <td><?php echo htmlspecialchars($reservation['activity']); ?></td>
                <td><?php echo htmlspecialchars($reservation['nomComplet_coach']); ?></td>
                <td><?php echo htmlspecialchars($reservation['status']); ?></td>
                <td>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                        <select name="new_status">
                            <option value="Confirmed">Confirmed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" name="modifier">Modifier</button>
                    </form>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                        <button type="submit" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Formulaire our ajouter des activite  -->

</body>
</html>