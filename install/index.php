<?php

$host="localhost"; 

$root="root"; 
$root_password=""; 

$user = 'lbc';
$pass = 'lbc';
$db   = 'lbc'; 

$req = file_get_contents ("./lbc.sql");
$req = str_replace("\n","",$req);
$req = str_replace("\r","",$req);

    try {
        $dbh = new PDO("mysql:host=$host", $root, $root_password);
        $dbh->exec("CREATE DATABASE `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;") 
        or die(print_r($dbh->errorInfo(), true));

    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }

    // Bug encodage des données
    $dbh->exec($req) or die(print_r($dbh->errorInfo(), true));
    echo "Installation de la base '".$db."' terminée !";
?>