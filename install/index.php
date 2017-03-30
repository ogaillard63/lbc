<?php

$host="localhost"; 
$root="root"; 
$root_password=""; 

$req = file_get_contents ("./lbc.sql");
$req = str_replace("\n","",$req);
$req = str_replace("\r","",$req);

    try {
        $dbh = new PDO("mysql:host=$host", $root, $root_password);
        $dbh->exec($req) or die(print_r($dbh->errorInfo(), true));
        echo "Installation de la base de données terminée !";
    } catch (PDOException $e) {
        die("DB ERROR: ". $e->getMessage());
    }
?>