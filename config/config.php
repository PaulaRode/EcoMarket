<?php 
// This file is used to configure the database connection and other settings for the EcoMarket application.

require_once __DIR__ . "/../classes/DataBase.php" ;

$database = new DataBase();
$conn = $database->getConnection();

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}
?>
