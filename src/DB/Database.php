<?php

namespace App\DB;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $database;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    /**
     * @var PDO
     */
    private static $conn;

    /**
     * @var self
     */
    private static $instance;

    private function __construct(string $host, string $database, string $user, string $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;

        $this->getDb();
    }

    public function getDb(): PDO
    {
        if (!self::$conn) {
            try {
                $dsn = "mysql:host=$this->host;dbname=$this->database";

                self::$conn = new PDO($dsn, $this->user, $this->password);

                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\Throwable $th) {
                echo $th->getMessage();
                exit();
            }
        }

        return self::$conn;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            $host = $_ENV['DATABASE_SERVER'];
            $database = $_ENV['DATABASE_NAME'];
            $user = $_ENV['DATABASE_USERNAME'];
            $password = $_ENV['DATABASE_PASSWORD'];

            self::$instance = new self($host, $database, $user, $password);
        }

        return self::$instance;
    }

    private function setParams($statement, $parameters = array()): void
    {
        foreach ($parameters as $key => $value) {
            $statement->bindParam($key, $value);
        }
    }

    public function query($rawQuery, $params = array()): PDOStatement|PDOException
    {
        try {
            $stmt = self::$conn->prepare($rawQuery);

            $this->setParams($stmt, $params);

            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function select($rawQuery, $params = []): array
    {

        try {
            $stmt = $this->query($rawQuery, $params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}