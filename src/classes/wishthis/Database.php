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
        public string $host,
        public string $database,
        public string $user,
        public string $password,
    ) {
        $dsn     = 'mysql:host=' . $this->host . ';dbname=' . $this->database . ';port=3306;charset=utf8';
        $options = array('placeholders' => array());

        $this->pdo = new \PDO($dsn, $this->user, $this->password, $options);
    }

    public function query(string $query, array $placeholders = array()): \PDOStatement
    {
        $statement = $this->pdo->prepare($query, array(\PDO::FETCH_ASSOC));

        foreach ($placeholders as $name => $value) {
            switch (gettype($value)) {
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

        if (!is_iterable($tables)) {
            return false;
        }

        foreach ($tables as $table_kv) {
            $table = reset($table_kv);

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
            array(
                'table_name'  => $table_to_check,
                'column_name' => $column_to_check,
            )
        )
        ->fetch();
        $exists = false !== $result;

        return $exists;
    }
}
