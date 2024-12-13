<?php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "systeme_gesoin_salle"; 


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8mb4");
    
    $sql = "ALTER TABLE activities CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    
    // echo "Connexion réussie et encodage de la table modifié avec succès.";
} catch(PDOException $e) {
    die('Connexion échouée : ' . $e->getMessage());
}
?>