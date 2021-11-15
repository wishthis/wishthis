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

    public function __construct(
        public string $host,
        public string $database,
        public string $user,
        public string $password,
    ) {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';port=3306;charset=utf8';
        $options = array();

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->password, $options);
        } catch (\PDOException $PDOE) {
            throw new \PDOException($PDOE->getMessage(), (int)$PDOE->getCode());
        }
    }

    public function query(string $query): void
    {
        $this->pdo->query($query);
    }

    public function getOption(string $key): string
    {
        $option = $this->pdo->query(
            'SELECT * FROM `options`
             WHERE `key` = "' . $key . '";',
            \PDO::FETCH_ASSOC
        )->fetch();

        return $option['value'];
    }
}
