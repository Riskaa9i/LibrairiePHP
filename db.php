<?php
 
try {
   
    $username = "root";
    $password = "";
 
    $dsn = "mysql:dbname=librairie_app;host=localhost;charset=utf8";
 
   
    $options = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
 
    $pdo = new PDO($dsn, $username, $password, $options);
 

} catch (PDOException $error) {
 
 
    die("Il y a une erreur : " . $error->getMessage());
}

?>