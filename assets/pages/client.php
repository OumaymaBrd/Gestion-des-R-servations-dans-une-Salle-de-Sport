<?php
    
    include 'connexion.php';


     $sql = "SELECT * FROM activities ";
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
                <li><a href="../../index.html">Accueil</a></li>
                <li><a href="../pages/activite.html">Activités</a></li>
                <li><a href="../pages/contact.html">Contact</a></li>
            </ul>
        </nav>
    </header>
    <!-- <img src='" . htmlspecialchars($activity['image_url']) . "' alt='" . htmlspecialchars($activity['name']) . "'> -->
    <?php
if ($activities) {
    echo "<div class='container'>
        <h2>Nos Activités</h2>
        <div class='activities'>";

    foreach ($activities as $activity) {
        echo "<div class='activity-card'>
        
            <h3>" . htmlspecialchars($activity['name']) . "</h3>
            <p>" . htmlspecialchars($activity['description']) . "</p>
            <a href='reservation.php?id=" . htmlspecialchars($activity['activity_id']) . "' class='btn'>Réserver</a>
        </div>";
    }

    echo "</div>
    </div>";
} else {
    echo "<p>Aucune activité trouvée.</p>";
}
?>
    <footer>
        <p>&copy; 2024 FitnessPro Gym. Tous droits réservés.</p>
    </footer>
</body>
</html>