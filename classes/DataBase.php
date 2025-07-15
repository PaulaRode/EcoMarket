<?php 

class DataBase {
    private $host = "localhost";
    private $db_name = "EcoMarket";
    private $username = "root";
    private $password = "";
    public $conn;


    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }

}

?>