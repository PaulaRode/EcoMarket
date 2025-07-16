<?php
include_once './config/config.php';
include_once './classes/DataBase.php';
include_once './classes/Produto.php';

// Verifica se o ID foi fornecido na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $db = DataBase::getConnection();

    $produto = new Produto($db);

    // Chama o método deletar com o ID como argumento
    if ($produto->deletar($id)) {
        header("Location: dashboard.php?msg=excluido");
        exit();
    } else {
        echo "Erro ao excluir o produto.";
    }
} else {
    echo "ID do produto não fornecido.";
}
?>
