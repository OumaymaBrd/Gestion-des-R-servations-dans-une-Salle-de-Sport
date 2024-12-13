<?php
include('connexion.php');
$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($lastname) || empty($firstname) || empty($email) || empty($phone)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        $sql = "INSERT INTO members (name, first_name, email, phone_number) VALUES (:lastname, :firstname, :email, :phone)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':lastname', $lastname);
        $stmt->bindParam(':firstname', $firstname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        if ($stmt->execute()) {
            $success_message = "Données insérées avec succès.";
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
    <link rel="stylesheet" href="assets/css/authentification.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="https://via.placeholder.com/150x50?text=FitnessPro" alt="FitnessPro Logo">
        </div>
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
            <button type="submit">S'authentifier</button>
        </form>
    </div>
</body>
</html>
