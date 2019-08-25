<?php

class Database {
    private static $instance;
    private $pdo;
    private $host;
    private $database;
    private $username;
    private $password;
    private $charset = 'utf8mb4';

    public function __construct($host, $database, $username, $password) {
        if (self::$instance !== null) {
            throw new Exception('Database instance already exists');
        }

        $this->host     = $host;
        $this->database = $database;
        $this->username = $username;
        $this->passord  = $password;

        $dsn = "mysql:host={$this->host};dbname={$this->database};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new self(
                getenv('MYSQL_HOST'),
                getenv('MYSQL_DATABASE'),
                getenv('MYSQL_USERNAME'),
                getenv('MYSQL_PASSWORD')
            );
        }

        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function pdo() {
        return $this->pdo;
    }

    public function query($sql, $args = []) {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetch();
    }

    public function queryAll($sql, $args = []) {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($args);
        return $stmt->fetchAll();
    }

    public function insert($sql, $args) {
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($args);
        return $this->pdo()->lastInsertId();
    }
}