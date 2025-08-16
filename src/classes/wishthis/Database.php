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
    /**
     * Private
     */
    private int $lastInsertId;

    /**
     * Public
     */
    public \PDO $pdo;

    public function __construct(
        public string $engine,
        public string $host,
        public string $database,
        public string $user,
        public string $password,
    ) {
    }

    public function connect(): void
    {
        $dsn = match ($this->engine) {
            'mysql'  => \sprintf(
                'mysql:host=%1$s;dbname=%2$s;port=3306;charset=utf8mb4',
                $this->host, $this->database
            ),
            'sqlite' => \sprintf(
                'sqlite:%1$s',
                ROOT . '/database.sqlite'
            ),
        };

        $options = ['placeholders' => []];

        $this->pdo = new \PDO($dsn, $this->user, $this->password, $options);
    }

    public function query(string $query, array $placeholders = []): \PDOStatement|false
    {
        $statement = $this->pdo->prepare($query, [\PDO::FETCH_ASSOC]);

        foreach ($placeholders as $name => $value) {
            switch (\gettype($value)) {
                case 'boolean':
                    $statement->bindValue($name, $value, \PDO::PARAM_BOOL);
                    break;

                case 'integer':
                    $statement->bindValue($name, $value, \PDO::PARAM_INT);
                    break;

                case 'NULL':
                    $statement->bindValue($name, $value, \PDO::PARAM_NULL);
                    break;

                default:
                    $statement->bindValue($name, $value, \PDO::PARAM_STR);
                    break;
            }
        }

        $statement->execute();

        $this->lastInsertId = $this->pdo->lastInsertId();

        return $statement;
    }

    public function lastInsertId(): int
    {
        return $this->lastInsertId;
    }

    public function tableExists(string $table_to_check): bool
    {
        $tables = $this
        ->query('SHOW TABLES;')
        ->fetchAll();

        if (!\is_iterable($tables)) {
            return false;
        }

        foreach ($tables as $table_kv) {
            $table = \reset($table_kv);

            if ($table === $table_to_check) {
                return true;
            }
        }

        return false;
    }

    public function columnExists(string $table_to_check, string $column_to_check): bool
    {
        $result = $this
        ->query(
            'SELECT *
               FROM `INFORMATION_SCHEMA`.`COLUMNS`
              WHERE `TABLE_NAME`  = :table_name
                AND `COLUMN_NAME` = :column_name',
            [
                'table_name'  => $table_to_check,
                'column_name' => $column_to_check,
            ]
        )
        ->fetch();
        $exists = false !== $result;

        return $exists;
    }

    public function enableForeignKeyChecks(): void
    {
        $disableForeignKeyChecks = match ($this->engine) {
            'mysql'  => 'SET foreign_key_checks = 1;',
            'sqlite' => 'PRAGMA foreign_keys = ON;',
        };

        $this->query($disableForeignKeyChecks);
    }

    public function disableForeignKeyChecks(): void
    {
        $disableForeignKeyChecks = match ($this->engine) {
            'mysql'  => 'SET foreign_key_checks = 0;',
            'sqlite' => 'PRAGMA foreign_keys = OFF;',
        };

        $this->query($disableForeignKeyChecks);
    }
}
