<?php

namespace App\Model;

use App\DB\Database;
use PDOException;
use PDOStatement;

class DataObject {
    /**
     * Other database fields
     *
     * @var array
     */
    protected $db = [];

    /**
     * Data stored in this objects database record. An array indexed by fieldname.
     *
     * @var array
     */
    protected $record;

    /**
     * Custom definition for object table name
     *
     * @var string
     */
    protected $tableName;

    protected $databaseConnection;

    /**
     * Default DB fields for DataObject
     *
     * @var array
     */
    protected $default_fields = [
        'ID' => 'Int',
        'LastEdited' => 'Datetime',
        'Created' => 'Datetime',
    ];

    public function __construct()
    {
        $this->record = [];

        $this->record['ID'] = 0;

        $this->databaseConnection = Database::getInstance();
    }

    public function __set(string $name, mixed $value): void {
        $this->record[$name] = $value;
    }

    public function __get(string $name) {
        if (array_key_exists($name, $this->record)) {
            return $this->record[$name];
        }
        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    /**
     * Return string values for query
     *
     * @return string
     */
    private function queryValues(): string
    {
        $values = "";

        foreach ($this->record as $key => $value) {
            $values .= "'$value', ";
        }

        $values = rtrim($values, ", ");

        return $values;
    }

    /**
     * Write record to database
     *
     * @return PDOStatement
     */
    public function write(): PDOStatement|PDOException
    {
        // First check if Table actually exists
        $this->checkTable();

        // if it's already in DB, we'll update the record
        // otherwise we'll create the record and set the ID
        $now = date("Y-m-d H:i:s");
        if ($this->isInDB()) {
            $query = "UPDATE ". self::getTableName() . " SET Email = '". $this->record['Email'] . "', LastEdited = '$now' WHERE ID = ". $this->record['ID'];
        } else {
            $recordFields = implode(", ", array_keys($this->record));
            $query = "INSERT INTO ". self::getTableName() . "(".$recordFields.", Created, LastEdited)";
            $query .= " VALUES(".$this->queryValues().", '$now', '$now')";
        }

        try {
            $result = $this->databaseConnection->query($query);
        } catch (PDOException $e) {
            throw $e;
        }

        // need to set ID after insert

        return $result;
    }

    /**
     * Check if record is already in DB
     *
     * @return boolean
     */
    public function isInDB(): bool
    {
        if ($this->record['ID'] === 0) return false;

        $result = self::get("ID = ". $this->record['ID']);

        return count($result) > 0 ? true : false;
    }

    /**
     * Return table name for data object
     *
     * @return string
     */
    private function getTableName(): string
    {
        return !empty($this->tableName) ? $this->tableName : str_replace(__NAMESPACE__ . '\\', '', get_class($this));
    }

    private function checkTable(): void
    {
        $tableName = $this->getTableName();
        $query = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'db' AND table_name = '$tableName'";
        $result = $this->databaseConnection->query($query);

        if(!$result->fetchColumn()) $this->createTable($tableName);
    }

    private function createTable($tableName): void
    {
        $columns = array_merge($this->default_fields, $this->db);
        $columnsString = "";

        foreach ($columns as $key => $value) {
            // Concatenate the key and value with a comma separator
            $columnsString .= $key . " " . $value
            ;
            if ($key === 'ID')
                $columnsString .= " NOT NULL AUTO_INCREMENT,";
            else
                $columnsString .= ",";
        }

        $columnsString = rtrim($columnsString, ',');

        $query = "CREATE TABLE $tableName ($columnsString, PRIMARY KEY (ID));";

        try {
            $this->databaseConnection->query($query);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Get records for this data object
     *
     * TODO: improve appending o filters and sorting, values could come in key/value array
     *
     * @param string|null $filter
     * @param string|null $sort
     * @param integer|null $limit
     * @return array
     */
    public static function get(
        string $filter = "",
        string $sort = "",
        int $limit = null
    ): array|PDOException
    {
        $databaseConnection = Database::getInstance();

        $query = "SELECT * FROM ". str_replace(__NAMESPACE__ . '\\', '', get_called_class());
        if (!empty($filter) && is_string($filter)) $query .= " WHERE ". $filter;
        if (!empty($sort) && is_string($sort)) $query .= " ORDER BY ". $sort;
        if (!empty($limit) && is_int($limit)) $query .= " LIMIT ". $limit;

        try {
            $result = $databaseConnection->query($query);
        } catch (PDOException $e) {
            throw $e;
        }

        return $result;
    }
}