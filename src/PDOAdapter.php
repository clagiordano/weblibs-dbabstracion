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
    private $dbConnection;

    /**
     * Constructor
     *
     * @param  string $dbHost
     * @param  string $dbUser
     * @param  string $dbPassword
     * @param  string $dbName
     * @param string $dbDriver
     * @param  string $dbCharset
     *
     * @param bool $isPersistent
     * @internal param string $driver
     */
    public function __construct(
        $dbHost,
        $dbUser,
        $dbPassword,
        $dbName,
        $dbDriver = "mysql",
        $dbCharset = "utf8",
        $isPersistent = true
    ) {
        $this->dbHostname = $dbHost;
        $this->dbUsername = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;

        $this->dbDriver = $dbDriver;
        $this->dbCharset = $dbCharset;

        $this->driverOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => $isPersistent,
        ];
    }

    /**
     * @brief Database connection
     * @return PDO
     * @throws \Exception
     */
    public function connect()
    {
        /*
         * Try to connect to database
         */
        try {
            $this->dbConnection = new PDO(
                "{$this->dbDriver}:host={$this->dbHostname};dbname={$this->dbName};charset={$this->dbCharset}",
                "{$this->dbUsername}",
                "{$this->dbPassword}",
                $this->driverOptions
            );

            //$this->Logger = new Logger($this->dbConnection);
        } catch (\PDOException $ex) {
            // Error during database connection, check params.
            throw new \Exception(__METHOD__ . ": " . $ex->getMessage());
        }

        return $this->dbConnection;
    }

    /**
     * Close automatically the database connection when the instance
     * of the class is destroyed
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Close explicitly the database connection
     *
     * @return boolean
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
     * @param $queryString
     * @return mixed
     */
    public function query($queryString)
    {
        // TODO: Implement query() method.
    }

    /**
     * @return mixed
     */
    public function fetch()
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param $table
     * @param string $conditions
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null)
    {
        // TODO: Implement select() method.
    }

    /**
     * @param $table
     * @param array $data
     * @return mixed
     */
    public function insert($table, array $data)
    {
        // TODO: Implement insert() method.
    }

    /**
     * @param $table
     * @param array $data
     * @param $conditions
     * @return mixed
     */
    public function update($table, array $data, $conditions)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $table
     * @param $conditions
     * @return mixed
     */
    public function delete($table, $conditions)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        // TODO: Implement getInsertId() method.
    }

    /**
     * @return mixed
     */
    public function countRows()
    {
        // TODO: Implement countRows() method.
    }

    /**
     * @return mixed
     */
    public function getAffectedRows()
    {
        // TODO: Implement getAffectedRows() method.
    }
}
