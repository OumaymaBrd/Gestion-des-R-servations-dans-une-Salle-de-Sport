<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessPro Gym - Contact</title>
    <link rel="stylesheet" href="../css/style_contact.css">
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
        <h2>Contactez-nous</h2>
        <div class="contact-form">
            <form action="#" method="post">
                <div class="form-group">
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message :</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn">Envoyer</button>
            </form>
        </div>
        <div class="contact-info">
            <h3>Informations de contact</h3>
            <p>Adresse : 123 Rue du Sport, 75000 Paris</p>
            <p>Téléphone : 01 23 45 67 89</p>
            <p>Email : info@fitnessprogym.com</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 FitnessPro Gym. Tous droits réservés.</p>
    </footer>
</body>
</html>