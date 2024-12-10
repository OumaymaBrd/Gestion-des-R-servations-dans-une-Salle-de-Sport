<?php

$servername="localhost";
$username="root";
$password='';


try{
    $conn=new pdo("mysql:host=$servername; dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo 'connexion echoue: '.$e->getMessage();
}






?>