<?php

namespace clagiordano\weblibs\dbabstraction;

use PDO;

/**
 * @class \clagiordano\weblibs\dbabstraction\PDOAdapter
 * @brief
 */
class PDOAdapter implements DatabaseAdapterInterface
{
    private $dbHostname;
    private $dbUsername;
    private $dbPassword;
    private $dbName;
    private $dbDriver;
    private $dbCharset;
    private $driverOptions;
    /** @var PDO $dbConnection */
    private $dbConnection;
    private $executionStatus;
    private $lastInsertedId;
    /** @var \PDOStatement $resourceHandle */
    private $resourceHandle;

    /**
     * Constructor.
     *
     * @param string $dbHost
     * @param string $dbUser
     * @param string $dbPassword
     * @param string $dbName
     * @param string $dbDriver
     * @param string $dbCharset
     * @param bool   $isPersistent
     */
    public function __construct(
        $dbHost,
        $dbUser,
        $dbPassword,
        $dbName,
        $dbDriver = 'mysql',
        $dbCharset = 'utf8',
        $isPersistent = true
    ) {
        $this->dbHostname = $dbHost;
        $this->dbUsername = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;

        $this->dbDriver = $dbDriver;
        $this->dbCharset = $dbCharset;

        $this->driverOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_PERSISTENT => $isPersistent
        ];
    }

    /**
     * @brief Database connection
     *
     * @return PDO
     *
     * @throws \Exception
     */
    public function connect()
    {
        /*
         * Try to connect to database
         */
        try {
            $this->dbConnection = new \PDO(
                "{$this->dbDriver}:host={$this->dbHostname};dbname={$this->dbName};charset={$this->dbCharset}",
                "{$this->dbUsername}",
                "{$this->dbPassword}",
                $this->driverOptions
            );
        } catch (\PDOException $ex) {
            // Error during database connection, check params.
            throw new \InvalidArgumentException(
                __METHOD__.': '.$ex->getMessage()
            );
        }

        return $this->dbConnection;
    }

    /**
     * Close automatically the database connection when the instance
     * of the class is destroyed.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Close explicitly the database connection.
     *
     * @return bool
     */
    public function disconnect()
    {
        if ($this->dbConnection === null) {
            return false;
        }

        $this->dbConnection = null;

        return true;
    }

    /**
     * Execute the specified query.
     *
     * @param $queryString
     *
     * @return mixed
     */
    public function query($queryString, array $queryValues = [])
    {
        if (!is_string($queryString) || empty($queryString)) {
            throw new \InvalidArgumentException(
                __METHOD__.': The specified query is not valid.'
            );
        }

        $this->connect();
        $this->resourceHandle = $this->dbConnection->prepare($queryString);

        try {
            // start transaction
            $this->dbConnection->beginTransaction();

            // execute the query and return a status
            $this->executionStatus = $this->resourceHandle->execute($queryValues ?: null);

            // get last inserted id if present
            $this->lastInsertedId = $this->dbConnection->lastInsertId();

            // finally execute the query
            $this->dbConnection->commit();
        } catch (\PDOException $ex) {
            // If an error occurs, execute rollback
            $this->dbConnection->rollBack();

            // Return execution status to false
            $this->executionStatus = false;
            $this->resourceHandle->closeCursor();

            throw new \RuntimeException(
                __METHOD__.": {$ex->getMessage()}"
            );
        }

        return $this->resourceHandle;
    }

    /**
     * @return array|false
     */
    public function fetch()
    {
        if ($this->resourceHandle !== null) {
            if (($row = $this->resourceHandle->fetch(\PDO::FETCH_ASSOC)) === false) {
                $this->freeResult();
            }

            return $row;
        }

        return false;
    }

    /**
     * Perform a SELECT statement
     *
     * @param $table
     * @param string $conditions
     * @param string $fields
     * @param string $order
     * @param null   $limit
     * @param null   $offset
     *
     * @return mixed
     */
    public function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        $query = 'SELECT '.$fields.' FROM '.$table;
        $query .= ($conditions) ? ' WHERE '.$conditions : '';
        $query .= ($limit) ? ' LIMIT '.$limit : '';
        $query .= ($offset && $limit) ? ' OFFSET '.$offset : '';
        $query .= ($order) ? ' ORDER BY '.$order : '';

        $this->query($query);

        return $this->countRows();
    }

    /**
     * Perform a INSERT statement
     *
     * @param $table
     * @param array $data
     *
     * @return mixed
     */
    public function insert($table, array $data)
    {
        $nameFields = join(',', array_keys($data));
        $preparedValues = $this->prepareValues($data);
        $keyValues = join(",", array_keys($preparedValues));
        $queryString = "INSERT INTO {$table} ({$nameFields}) VALUES ({$keyValues});";

        $this->query($queryString, $preparedValues);

        return $this->getInsertId();
    }

    /**
     * Preparate values for execute
     *
     * @param array $arrayData
     * @return string
     */
    private function prepareValues($arrayData)
    {
        $arrayData = array_values($arrayData);

        $preparedValues = [];
        $vNumber = 1;
        foreach ($arrayData as $value) {
            $preparedValues[":value{$vNumber}"] = "$value";
            $vNumber++;
        }

        unset($arrayData);
        unset($vNumber);

        return $preparedValues;
    }

    /**
     * Perform a UPDATE statement
     *
     * @param $table
     * @param array $data
     * @param $conditions
     *
     * @return mixed
     */
    public function update($table, array $data, $conditions)
    {
        $nameFields = array_keys($data);
        $preparedValues = $this->prepareValues($data);
        $queryString = "UPDATE {$table} SET ";

        $fNumber = 0;
        foreach ($preparedValues as $key => $value) {
            $queryString .= "{$nameFields[$fNumber]} = {$key}, ";
            $fNumber++;
        }

        $queryString = preg_replace('/,\ $/', ' ', $queryString);
        $queryString .= "WHERE {$conditions};";

        $this->query($queryString, $preparedValues);

        return $this->getAffectedRows();
    }

    /**
     * Perform a DELETE statement
     *
     * @param $table
     * @param $conditions
     *
     * @return mixed
     */
    public function delete($table, $conditions)
    {
        $queryString = "DELETE FROM {$table} WHERE {$conditions}";

        $this->query($queryString);

        return $this->getAffectedRows();
    }

    /**
     * @return integer
     */
    public function getInsertId()
    {
        return (integer)$this->lastInsertedId;
    }

    /**
     * @return mixed
     */
    public function countRows()
    {
        $countRows = 0;

        if (!is_null($this->resourceHandle)) {
            $countRows = $this->resourceHandle->rowCount();
        }

        return $countRows;
    }

    /**
     * @return mixed
     */
    public function getAffectedRows()
    {
        $affectedRows = 0;

        if (!is_null($this->resourceHandle)) {
            $affectedRows = $this->resourceHandle->rowCount();
        }

        return $affectedRows;
    }

    /**
     * @brief
     * @return bool
     */
    public function freeResult()
    {
        if ($this->resourceHandle === null) {
            return false;
        }

        $this->resourceHandle->closeCursor();

        return true;
    }

    /**
     * Escape the specified value
     */
    public function quoteValue($value)
    {
        if ($value === null) {
            $value = 'NULL';
        } elseif (!is_numeric($value)) {
            $value = "'{$value}'";
        }

        return $value;
    }
}
