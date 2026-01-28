<?php

class Database {
    private $host = 'localhost';
    private $db_name = 'biblioteca';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;
        
        // TODO: Implementar la conexión a la base de datos utilizando PDO
        
        return $this->conn;
    }
}
