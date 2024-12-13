<?php

include('connexion.php');
if (!isset($_GET['matricule'])) {
    die("Accès refusé. Matricule manquant.");
}

$matricule = htmlspecialchars($_GET['matricule'], ENT_QUOTES, 'UTF-8');
$message = "";

$reservedActivities = [];
try {
    $sql = "SELECT activity FROM reservations WHERE Matricule = :matricule AND status = 'Confirmed'";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':matricule' => $matricule]);
    $reservedActivities = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
} catch (PDOException $e) {
    $message = "Erreur lors de la récupération des réservations : " . $e->getMessage();
}
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
                <li><a href="../../index.html">Accueil</a></li>
                <li><a href="../pages/activite.html">Activités</a></li>
                <li><a href="../pages/contact.html">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <h2>Nos Activités</h2>
        <div class="activities">
            <?php
            $activities = [
                'Yoga' => '../images/yoga.jpg',
                'Musculation' => '../images/methode-de-muscu.jpg',
                'Spinning' => '../images/spinning.jpg',
                'Pilates' => '../images/Pilates.webp',
                'Zumba' => '../images/zomba.jpg',
                'Boxe' => '../images/box.webp'
            ];

            foreach ($activities as $activity => $image): ?>
                <div class="activity-card">
                    <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($activity) ?>">
                    <h3><?= htmlspecialchars($activity) ?></h3>
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?matricule=<?= urlencode($matricule) ?>" method="post">
                        <input type="hidden" name="activity" value="<?= htmlspecialchars($activity) ?>">
                        <?php if (in_array($activity, $reservedActivities)): ?>
                            <button type="submit" name="annuler" class="btn cancel">Annuler</button>
                        <?php else: ?>
                            <button type="submit" name="reserver" class="btn">Réserver</button>
                        <?php endif; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FitnessPro Gym. Tous droits réservés.</p>
    </footer>
</body>
</html>