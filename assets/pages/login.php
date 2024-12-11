<?php
include('connexion.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données soumises
    $matricule = $_POST['Matricule'];
    $password = $_POST['password'];

    // Préparer une requête pour vérifier le matricule et le poste
    $sql = "SELECT * FROM tableau_authentifier WHERE Matricule = :matricule AND Post IS NOT NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':matricule', $matricule);
    $stmt->execute();

    // Vérifier si un utilisateur a été trouvé
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        $id = $user['id']; // Récupérer l'ID de l'utilisateur depuis la base de données

        // Rediriger en fonction du poste et ajouter ID et Matricule dans l'URL
        if ($user['Post'] == 'Coach') {
            header("Location: Coach.php?id=$id&matricule=$matricule");
            exit;
        } elseif ($user['Post'] == 'Administrateur') {
            header("Location: admin.php?id=$id&matricule=$matricule");
            exit;
        } elseif ($user['Post'] == 'Client') {
            header("Location: client.php?id=$id&matricule=$matricule");
            exit;
        } else {
            $error_message = "Poste inconnu. Veuillez contacter l'administrateur.";
        }
    } else {
        $error_message = "Matricule ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FitnessPro Gym</title>
    <link rel="stylesheet" href="../css/style_login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <!-- Logo -->
        </div>
        <h1>Connexion</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form action="#" method="post">
            <input type="text" name="Matricule" placeholder="Matricule" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
        <div class="links">
            <a href="#">Mot de passe oublié ?</a> | <a href="#">S'inscrire</a>
        </div>
    </div>
</body>
</html>
