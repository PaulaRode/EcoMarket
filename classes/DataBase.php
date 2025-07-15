<?php
class DataBase {
    public static function getConnection() {
        // Altere os dados conforme seu ambiente
        $host = '127.0.0.1';
        $db = 'ecomarket';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Em produção, não exiba o erro diretamente
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
}
