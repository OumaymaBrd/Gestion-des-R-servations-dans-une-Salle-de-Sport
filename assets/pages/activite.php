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
                <li><a href="../pages/activite.php">Activités</a></li>
                <li><a href="../pages/contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">


    <?php
    
    include 'connexion.php';


     $sql = "SELECT * FROM activities ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    if ($activities) {
        foreach ($activities as $activity) {
            echo "ID: " . $activity['activity_id '] . "<br>";
            echo "Nom: " . $activity['name'] . "<br>";
            echo "Description: " . $activity['description'] . "<br>";
            echo "<hr>";
        }
    } else {
        echo "Aucune activité trouvée.";
    }
    
    
    ?>
        <h2>Nos Activités</h2>
        <div class="activities">
            <div class="activity-card">
                <img src="../images/yoga.jpg" alt="Yoga">
                <h3>Yoga</h3>
                <p>Détendez-vous et améliorez votre flexibilité avec nos cours de yoga.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
            <div class="activity-card">
                <img src="../images/methode-de-muscu.jpg" alt="Musculation">
                <h3>Musculation</h3>
                <p>Renforcez vos muscles avec notre équipement de pointe.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
            <div class="activity-card">
                <img src="../images/spinning.jpg" alt="Spinning">
                <h3>Spinning</h3>
                <p>Brûlez des calories avec nos cours de spinning intensifs.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
            <div class="activity-card">
                <img src="../images/Pilates.webp" alt="Pilates">
                <h3>Pilates</h3>
                <p>Renforcez votre corps et améliorez votre posture avec le Pilates.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
            <div class="activity-card">
                <img src="../images/zomba.jpg" alt="Zumba">
                <h3>Zumba</h3>
                <p>Dansez et brûlez des calories avec nos cours de Zumba énergiques.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
            <div class="activity-card">
                <img src="../images/box.webp" alt="Boxe">
                <h3>Boxe</h3>
                <p>Améliorez votre condition physique et votre confiance avec la boxe.</p>
                <a href="../pages/login.php" class="btn">Réserver</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FitnessPro Gym. Tous droits réservés.</p>
    </footer>
</body>
</html>