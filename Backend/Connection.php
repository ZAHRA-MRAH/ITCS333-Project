<?php 
$dsn = "mysql:host=localhost;dbname=project;charset=utf8mb4" ; 
$Username = "root"; 
$Password = ""; 

try{
$db = new PDO($dsn, $Username, $Password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    // connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>