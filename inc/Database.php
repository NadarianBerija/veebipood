<?php

class Database {
    private $conn;
    private $host;
    private $user;
    private $password;
    private $baseName;

    function __construct() {
        $this->host = 'localhost';
        $this->user = 'root';
        $this->password = '';
        $this->baseName = 'vihmart';
        $this->connect();
    }

    private function __clone() {}

    function __destruct() {
        $this->disconnect();
    }

    function connect() {
        if (!$this->conn) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->baseName};charset=utf8mb4", $this->user, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $e) {
                error_log($e->getMessage());
                die("Database connection error");
            }
        }
        return $this->conn;
    }

    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }

    function getOne($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getAll($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function executeRun($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }
}

