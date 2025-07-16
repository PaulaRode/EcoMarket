<?php
require_once 'config/config.php';

try {
    // Testa a conexão
    $stmt = $conn->query("SELECT 1");
    echo "Conexão com banco de dados OK!<br>";
    
    // Verifica se a tabela existe
    $stmt = $conn->query("SHOW TABLES LIKE 'tbUsu'");
    if ($stmt->rowCount() > 0) {
        echo "Tabela tbUsu existe!<br>";
        
        // Verifica se há usuários cadastrados
        $stmt = $conn->query("SELECT COUNT(*) as total FROM tbUsu");
        $result = $stmt->fetch();
        echo "Total de usuários: " . $result['total'] . "<br>";
        
        // Mostra os usuários (apenas para debug)
        $stmt = $conn->query("SELECT id, nome, email FROM tbUsu LIMIT 5");
        echo "Usuários cadastrados:<br>";
        while ($row = $stmt->fetch()) {
            echo "- ID: " . $row['id'] . ", Nome: " . $row['nome'] . ", Email: " . $row['email'] . "<br>";
        }
    } else {
        echo "Tabela tbUsu NÃO existe!<br>";
    }
    
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?> 