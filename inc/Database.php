<?php
/**
 * File: inc/Database.php
 * Purpose: Handles database connections and basic CRUD operations using PDO.
 */

/**
 * Class Database
 * 
 * Manages the connection to the MySQL database and provides methods for executing queries.
 */
class Database {
    /** @var PDO|null The PDO connection object */
    private $conn;
    /** @var string The database host */
    private $host;
    /** @var string The database user */
    private $user;
    /** @var string The database password */
    private $password;
    /** @var string The database name */
    private $baseName;

    /**
     * Database constructor.
     * Initializes database credentials from environment variables and establishes a connection.
     */
    function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
        $this->baseName = $_ENV['DB_NAME'];
        $this->connect();
    }

    /**
     * Prevents cloning of the Database instance.
     */
    private function __clone() {}

    /**
     * Database destructor.
     * Ensures the database connection is closed when the object is destroyed.
     */
    function __destruct() {
        $this->disconnect();
    }

    /**
     * Establishes a connection to the database.
     * 
     * @return PDO The established PDO connection object.
     */
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

    /**
     * Closes the database connection.
     */
    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }

    /**
     * Executes a query and returns a single row of results.
     * 
     * @param string $query The SQL query string.
     * @param array $params An optional array of parameters to bind to the query.
     * @return array|false The fetched row as an associative array, or false on failure.
     */
    function getOne($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Executes a query and returns all rows of results.
     * 
     * @param string $query The SQL query string.
     * @param array $params An optional array of parameters to bind to the query.
     * @return array The fetched rows as an array of associative arrays.
     */
    function getAll($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Executes an SQL statement (INSERT, UPDATE, DELETE).
     * 
     * @param string $query The SQL query string.
     * @param array $params An optional array of parameters to bind to the query.
     * @return bool True on success, false on failure.
     */
    function executeRun($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Initiates a database transaction.
     * 
     * @return bool True on success, false on failure.
     */
     function beginTransaction() {
        return $this->conn->beginTransaction();
    }

    /**
     * Commits the current database transaction.
     * 
     * @return bool True on success, false on failure.
     */
    function commit() {
        return $this->conn->commit();
    }

    /**
     * Rolls back the current database transaction.
     * 
     * @return bool True on success, false on failure.
     */
    function rollBack() {
        return $this->conn->rollBack();
    }

    /**
     * Returns the ID of the last inserted row.
     * 
     * @return string The last insert ID.
     */
    function getLastId() {
        return $this->conn->lastInsertId();
    }
}
