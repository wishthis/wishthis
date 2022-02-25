<?php

/**
 * database.php
 *
 * Establishes a database connection using its credentials.
 *
 * @author Jay Trees <github.jay@grandel.anonaddy.me>
 */

namespace wishthis;

class Database
{
    public \PDO $pdo;

    private int $lastInsertId;

    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $password,
    ) {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';port=3306;charset=utf8';
        $options = array();

        $this->pdo = new \PDO($dsn, $this->user, $this->password, $options);
    }

    public function query(string $query): \PDOStatement
    {
        $statement = $this->pdo->query(
            $query,
            \PDO::FETCH_ASSOC
        );

        $this->lastInsertId = $this->pdo->lastInsertId();

        return $statement;
    }

    public function lastInsertId(): int
    {
        return $this->lastInsertId;
    }
}
