<?php 
// This file is used to configure the database connection and other settings for the EcoMarket application.

try {
    require_once __DIR__ . "/../classes/DataBase.php";
    
    $conn = DataBase::getConnection();
    
    if(!$conn){
        throw new Exception("Connection failed");
    }
} catch (Exception $e) {
    throw new Exception("Erro na configuração do banco: " . $e->getMessage());
}
?>
