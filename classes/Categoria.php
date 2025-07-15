<?php
class Categoria
{
    private $conn;
    private $table_name = "tbCategorias";

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function ler()
    {
        $query = "SELECT * FROM " . $this->table_name; 
        return $this->conn->query($query);
    }

}

