<?php
// db_connection.php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "systeme_gesoin_salle"; 

// Connexion à la base de données avec gestion des erreurs
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die('Connexion échouée : ' . $e->getMessage());
}
?>
