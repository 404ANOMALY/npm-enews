<?php
    $servername = "localhost";
    $username = "mglsi_user";
    $password = "passer";
    $dbname = "mglsi_news";

    try 
    {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    catch(PDOException $e) 
    {
        die("La connexion à la base de données a échoué : " . $e->getMessage());
    }
?>
