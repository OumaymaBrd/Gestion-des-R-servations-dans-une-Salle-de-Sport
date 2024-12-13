<?php
include('connexion.php');

function generateMatricule() {
    return 'YC' . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    if (empty($lastname) || empty($firstname) || empty($email) || empty($phone) || empty($password)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        $Post = 'Coach';
        $Matricule = generateMatricule();
        $nom_complet_membre = $firstname . '_' . $lastname;
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO tableau_authentifier (Matricule, nom_complet_membre, Post, email, phone_number, password) 
                VALUES (:matricule, :nom_complet, :post, :email, :phone, :password)";
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':matricule', $Matricule);
        $stmt->bindParam(':nom_complet', $nom_complet_membre);
        $stmt->bindParam(':post', $Post);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashed_password);
        
        if ($stmt->execute()) {
            $success_message = "Données insérées avec succès. Votre matricule est : " . $Matricule;
        } else {
            $error_message = "Erreur lors de l'insertion des données.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - FitnessPro Gym</title>
    <<link rel="stylesheet" href="../css/authentification.css">
  
</head>
<body>
    <div class="container">       
        <h1>Authentification</h1>
        
        <?php if (isset($error_message)): ?>
            <div class="error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Numéro de téléphone :</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Crrer un compte </button>
        </form>
    </div>
</body>
</html>