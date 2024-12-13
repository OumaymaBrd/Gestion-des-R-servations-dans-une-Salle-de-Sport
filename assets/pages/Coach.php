<?php
include 'connexion.php';

// Fetch activities
$sql_activities = "SELECT name FROM activities";
$stmt_activities = $conn->prepare($sql_activities);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

// Fetch coaches
$sql_coaches = "SELECT nom_complet_membre FROM tableau_authentifier WHERE Post='Coach'";
$stmt_coaches = $conn->prepare($sql_coaches);
$stmt_coaches->execute();
$coaches = $stmt_coaches->fetchAll(PDO::FETCH_ASSOC);

// Fetch membres 
$sql_membre = "SELECT nom_complet_membre FROM tableau_authentifier WHERE Post='Client'";
$stmt_membre = $conn->prepare($sql_membre);
$stmt_membre->execute();
$membres = $stmt_membre->fetchAll(PDO::FETCH_ASSOC);
$message = '';





// Fetch existing reservations
$sql_reservations = "SELECT r.id, r.Matricule, r.activity, r.status, t.nom_complet_membre, t.nomComplet_coach 
                     FROM reservations r 
                     JOIN tableau_authentifier t ON r.Matricule = t.Matricule
                     
                     ";
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

        /*  */
       
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    
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

    <?php
include 'connexion.php';

// Fetch membres
$sql_membres = "SELECT nom_complet_membre FROM tableau_authentifier WHERE Post='Client'";
$stmt_membres = $conn->prepare($sql_membres);
$stmt_membres->execute();
$membres = $stmt_membres->fetchAll(PDO::FETCH_ASSOC);

// Fetch coaches
$sql_coaches = "SELECT nom_complet_membre FROM tableau_authentifier WHERE Post='Coach'";
$stmt_coaches = $conn->prepare($sql_coaches);
$stmt_coaches->execute();
$coaches = $stmt_coaches->fetchAll(PDO::FETCH_ASSOC);

// Fetch activities
$sql_activities = "SELECT name FROM activities";
$stmt_activities = $conn->prepare($sql_activities);
$stmt_activities->execute();
$activities = $stmt_activities->fetchAll(PDO::FETCH_ASSOC);

$message = '';

if(isset($_POST['Inscription_activite'])){
    $nom_complet_membre = $_POST['nom_complet_membre'] ?? '';
    $nomComplet_coach = $_POST['nomComplet_coach'] ?? '';
    $Admit_activite = $_POST['Admit_activite'] ?? '';
    $activity_nom = $_POST['activity_nom'] ?? '';

    if (!empty($nom_complet_membre) && !empty($nomComplet_coach) && !empty($Admit_activite) && !empty($activity_nom)) {
        try {
            $sql_Inscription_activite = "INSERT INTO tableau_activite 
                                         (nom_complet_membre, Admit_activite, nomComplet_coach, activity_nom) 
                                         VALUES (:nom_complet_membre, :Admit_activite, :nomComplet_coach, :activity_nom)";

            $stmt = $conn->prepare($sql_Inscription_activite);
            $stmt->bindParam(':nom_complet_membre', $nom_complet_membre);
            $stmt->bindParam(':Admit_activite', $Admit_activite);
            $stmt->bindParam(':nomComplet_coach', $nomComplet_coach);
            $stmt->bindParam(':activity_nom', $activity_nom);

            if ($stmt->execute()) {
                $message = "Inscription à l'activité réussie.";
            } else {
                $message = "Erreur lors de l'inscription à l'activité.";
            }
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs du formulaire.";
    }
}

// Handle modification of Admit_activite
if(isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $new_status = $_POST['new_status'];
    $sql_update = "UPDATE tableau_activite SET Admit_activite = :status WHERE id = :id";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bindParam(':status', $new_status);
    $stmt_update->bindParam(':id', $id);
    if($stmt_update->execute()) {
        $message = "Statut mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour du statut.";
    }
}

// Handle deletion
if(isset($_POST['supprimer'])) {
    $id = $_POST['id'];
    $sql_delete = "DELETE FROM tableau_activite WHERE id = :id";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bindParam(':id', $id);
    if($stmt_delete->execute()) {
        $message = "Enregistrement supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression de l'enregistrement.";
    }
}

// Fetch existing inscriptions
$sql_inscriptions = "SELECT * FROM tableau_activite";
$stmt_inscriptions = $conn->prepare($sql_inscriptions);
$stmt_inscriptions->execute();
$inscriptions = $stmt_inscriptions->fetchAll(PDO::FETCH_ASSOC);
?>


    <h1>Gestion des activités</h1>
    
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <button onclick="toggleForm()">Afficher/Cacher le formulaire d'inscription</button>
    <button onclick="toggleTable()">Afficher/Cacher le tableau des inscriptions</button>

    <div id="inscriptionForm" class="hidden">
        <h2>Formulaire d'inscription à une activité</h2>
        <form action="" method="POST">
            <label for="membre">Nom Membre :</label>
            <select name="nom_complet_membre" id="membre" required>
                <option value="">Choisissez un membre</option>
                <?php foreach ($membres as $membre): ?>
                    <option value="<?php echo htmlspecialchars($membre['nom_complet_membre']); ?>">
                        <?php echo htmlspecialchars($membre['nom_complet_membre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="coach">Choisissez un coach :</label>
            <select name="nomComplet_coach" id="coach" required>
                <option value="">Sélectionnez un coach</option>
                <?php foreach ($coaches as $coach): ?>
                    <option value="<?php echo htmlspecialchars($coach['nom_complet_membre']); ?>">
                        <?php echo htmlspecialchars($coach['nom_complet_membre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="Admit_activite">Admit :</label>
            <select name="Admit_activite" id="Admit_activite" required>
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>

            <label for="activity">Nom activité :</label>
            <select name="activity_nom" id="activity" required>
                <option value="">Choisissez une activité</option>
                <?php foreach ($activities as $activity): ?>
                    <option value="<?php echo htmlspecialchars($activity['name']); ?>">
                        <?php echo htmlspecialchars($activity['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select> 

            <input type="submit" name="Inscription_activite" value="Inscrire à l'activité">
        </form>
    </div>

    <div id="inscriptionsTable" class="hidden">
        <h2>Tableau des inscriptions</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nom Membre</th>
                <th>Nom Coach</th>
                <th>Activité</th>
                <th>Admit</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($inscriptions as $inscription): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inscription['id']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['nom_complet_membre']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['nomComplet_coach']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['activity_nom']); ?></td>
                    <td><?php echo htmlspecialchars($inscription['Admit_activite']); ?></td>
                    <td>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                            <select name="new_status">
                                <option value="oui" <?php echo $inscription['Admit_activite'] == 'oui' ? 'selected' : ''; ?>>Oui</option>
                                <option value="non" <?php echo $inscription['Admit_activite'] == 'non' ? 'selected' : ''; ?>>Non</option>
                            </select>
                            <button type="submit" name="modifier">Modifier</button>
                        </form>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $inscription['id']; ?>">
                            <button type="submit" name="supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function toggleForm() {
            var form = document.getElementById("inscriptionForm");
            form.classList.toggle("hidden");
        }

        function toggleTable() {
            var table = document.getElementById("inscriptionsTable");
            table.classList.toggle("hidden");
        }
    </script>
    <!--  -->
<h1>Formulaire Demande Autorisation pour une activite </h1>
<form action="" method="POST">

    <label for="coach">Choisissez un coach :</label>
            <select name="coach" id="coach" required>
                <option value="">Sélectionnez un coach</option>
                <?php foreach ($coaches as $coach): ?>
                    <option value="<?php echo htmlspecialchars($coach['nom_complet_membre']); ?>">
                        <?php echo htmlspecialchars($coach['nom_complet_membre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

    <label for="name">Nom activité : </label>
    <input type="text" name="name" id="name" required> <br>

    <label for="description">Description : </label>
    <input type="text" name="description" id="description" required> <br>

    <label for="capacity">Capacité : </label>
    <input type="number" name="capacity" id="capacity" required> <br>

    <label for="start_date">Date de début : </label>
    <input type="date" name="start_date" id="start_date" required> <br>

    <label for="end_date">Date de fin : </label>
    <input type="date" name="end_date" id="end_date" required> <br>

    <label for="disponibility">Disponibilité : </label>
    <input type="text" name="disponibility" id="disponibility" required> <br>

    <input type="submit" name="Demander_autorisation_Admin" value="Demander autorisation Admin">
</form>


<?php
if(isset($_POST['Demander_autorisation_Admin'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $capacity = $_POST['capacity'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $disponibility = $_POST['disponibility'];
    $coach = $_POST['coach'];
    $validation_admin = 1; // Set to 0 initially, as it needs admin approval

    try {
        $conn->beginTransaction();

        $sql3 = "INSERT INTO activities 
                 (name, description, capacity, start_date, end_date, disponibility, validation_admin) 
                 VALUES (:name, :description, :capacity, :start_date, :end_date, :disponibility, :validation_admin)";

        $stmt3 = $conn->prepare($sql3);
        $stmt3->bindParam(':name', $name);
        $stmt3->bindParam(':description', $description);
        $stmt3->bindParam(':capacity', $capacity);
        $stmt3->bindParam(':start_date', $start_date);
        $stmt3->bindParam(':end_date', $end_date);
        $stmt3->bindParam(':disponibility', $disponibility);
        $stmt3->bindParam(':validation_admin', $validation_admin);

        $stmt3->execute();

        $conn->commit();
        $message = "Votre demande a été ajoutée avec succès et est en attente de validation par l'administrateur.";
    } catch(PDOException $e) {
        $conn->rollBack();
        $message = "Erreur lors de l'ajout de l'activité : " . $e->getMessage();
    }
}

if (!empty($message)) {
    echo "<p>" . htmlspecialchars($message) . "</p>";
}
?>


<!--  -->

<?php
include 'connexion.php';

// Fetch coaches
$sql_coaches = "SELECT nom_complet_membre FROM tableau_authentifier WHERE Post='Coach'";
$stmt_coaches = $conn->prepare($sql_coaches);
$stmt_coaches->execute();
$coaches = $stmt_coaches->fetchAll(PDO::FETCH_ASSOC);

$message = '';

// Handle member addition
if(isset($_POST['ajouter_membre'])){
    $matricule = $_POST['Matricule'] ?? '';
    $nom_complet_membre = $_POST['nom_complet_membre'] ?? '';
    $coach = $_POST['coach'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $email = $_POST['email'] ?? '';
    $post = 'Client';

    if (!empty($matricule) && !empty($nom_complet_membre) && !empty($coach) && !empty($email)) {
        try {
            $sql4 = "INSERT INTO tableau_authentifier (Matricule, nom_complet_membre, Post, nomComplet_coach, email, phone_number, Supprimer) 
                     VALUES (:Matricule, :nom_complet_membre, :post, :coach, :email, :tel, 0)";

            $stmt4 = $conn->prepare($sql4);
            $stmt4->bindParam(':Matricule', $matricule);
            $stmt4->bindParam(':nom_complet_membre', $nom_complet_membre);
            $stmt4->bindParam(':post', $post);
            $stmt4->bindParam(':coach', $coach);
            $stmt4->bindParam(':email', $email);
            $stmt4->bindParam(':tel', $tel);

            if ($stmt4->execute()) {
                $message = "Membre ajouté avec succès.";
            } else {
                $message = "Erreur lors de l'ajout du membre.";
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du membre : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Handle member modification
if(isset($_POST['modifier_membre'])){
    $id = $_POST['id'];
    $matricule = $_POST['Matricule'];
    $nom_complet_membre = $_POST['nom_complet_membre'];
    $coach = $_POST['coach'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];

    try {
        $sql_update = "UPDATE tableau_authentifier SET 
                       Matricule = :Matricule, 
                       nom_complet_membre = :nom_complet_membre, 
                       nomComplet_coach = :coach, 
                       email = :email, 
                       phone_number = :tel 
                       WHERE id = :id";

        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':Matricule', $matricule);
        $stmt_update->bindParam(':nom_complet_membre', $nom_complet_membre);
        $stmt_update->bindParam(':coach', $coach);
        $stmt_update->bindParam(':email', $email);
        $stmt_update->bindParam(':tel', $tel);
        $stmt_update->bindParam(':id', $id);

        if ($stmt_update->execute()) {
            $message = "Membre modifié avec succès.";
        } else {
            $message = "Erreur lors de la modification du membre.";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la modification du membre : " . $e->getMessage();
    }
}

// Handle member deletion (update Supprimer to 1)
if(isset($_POST['supprimer_membre'])){
    $id = $_POST['id'];

    try {
        $sql_delete = "UPDATE tableau_authentifier SET Supprimer = 1 WHERE id = :id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id);

        if ($stmt_delete->execute()) {
            $message = "Membre marqué comme supprimé avec succès.";
        } else {
            $message = "Erreur lors de la suppression du membre.";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la suppression du membre : " . $e->getMessage();
    }
}

// Fetch all members
$sql_membres = "SELECT * FROM tableau_authentifier WHERE Post='Client' AND Supprimer = 0";
$stmt_membres = $conn->prepare($sql_membres);
$stmt_membres->execute();
$membres = $stmt_membres->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des membres</title>
    <style>
        .hidden { display: none; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h1>Gestion des membres</h1>
    
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <button onclick="toggleElement('formSection')">Afficher/Cacher le formulaire d'ajout</button>
    <button onclick="toggleElement('tableSection')">Afficher/Cacher la liste des membres</button>

    <div id="formSection" class="hidden">
        <h2>Formulaire pour ajouter un membre à notre session</h2>
        <form action="" method="post">
            <label for="Matricule">Matricule :</label>
            <input type="text" name="Matricule" id="Matricule" required>

            <label for="nom_complet_membre">Nom complet :</label>
            <input type="text" name="nom_complet_membre" id="nom_complet_membre" required>

            <label for="coach">Choisissez un coach :</label>
            <select name="coach" id="coach" required>
                <option value="">Sélectionnez un coach</option>
                <?php foreach ($coaches as $coach): ?>
                    <option value="<?php echo htmlspecialchars($coach['nom_complet_membre']); ?>">
                        <?php echo htmlspecialchars($coach['nom_complet_membre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="tel">Numéro de téléphone :</label>
            <input type="tel" name="tel" id="tel">

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>

            <button type="submit" name="ajouter_membre">Ajouter Membre</button>
        </form>
    </div>

    <div id="tableSection" class="hidden">
        <h2>Liste des membres</h2>
        <table>
            <tr>
                <th>Matricule</th>
                <th>Nom complet</th>
                <th>Coach</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($membres as $membre): ?>
                <tr>
                    <form action="" method="post">
                        <input type="hidden" name="id" value="<?php echo $membre['id']; ?>">
                        <td><input type="text" name="Matricule" value="<?php echo htmlspecialchars($membre['Matricule']); ?>" required></td>
                        <td><input type="text" name="nom_complet_membre" value="<?php echo htmlspecialchars($membre['nom_complet_membre']); ?>" required></td>
                        <td>
                            <select name="coach" required>
                                <?php foreach ($coaches as $coach): ?>
                                    <option value="<?php echo htmlspecialchars($coach['nom_complet_membre']); ?>" 
                                            <?php echo ($coach['nom_complet_membre'] == $membre['nomComplet_coach']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($coach['nom_complet_membre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td><input type="tel" name="tel" value="<?php echo htmlspecialchars($membre['phone_number']); ?>"></td>
                        <td><input type="email" name="email" value="<?php echo htmlspecialchars($membre['email']); ?>" required></td>
                        <td>
                            <button type="submit" name="modifier_membre">Modifier</button>
                            <button type="submit" name="supprimer_membre" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');">Supprimer</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        function toggleElement(elementId) {
            var element = document.getElementById(elementId);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }
    </script>
</body>
</html>


</body>
</html>