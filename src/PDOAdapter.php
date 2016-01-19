<?php

namespace clagiordano\weblibs\dbabstraction;

use PDO;

/**
 * @class \clagiordano\weblibs\dbabstraction\PDOAdapter
 * @brief
 */
class PDOAdapter implements DatabaseAdapterInterface
{
    /**
     * Constructor
     *
     * @param  string $db_host
     * @param  string $db_user
     * @param  string $db_password
     * @param  string $db_name
     * @param string $db_driver
     * @param  string $db_charset
     *
     * @param bool $db_persistent
     * @internal param string $driver
     */
    public function __construct(
        $db_host,
        $db_user,
        $db_password,
        $db_name,
        $db_driver = "mysql",
        $db_charset = "utf8",
        $db_persistent = true
    )
    {
        $this->dbHostname = $db_host;
        $this->dbUsername = $db_user;
        $this->dbPassword = $db_password;
        $this->dbName = $db_name;

        $this->dbDriver = $db_driver;
        $this->dbCharset = $db_charset;

        $this->DriverOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => $db_persistent
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
                "$driver:host=$db_host;dbname=$db_name;charset=$db_charset",
                "$db_user",
                "$db_password",
                $this->DriverOptions
            );
            $this->Logger = new Logger($this->dbConnection);

            return $this->dbConnection;
        } catch (\PDOException $ex) {
            // Error during database connection, check params.
            throw new \Exception(__METHOD__ . ": " . $ex->getMessage());
        }
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
     */
    public function disconnect()
    {
        if ($this->dbConnection === null) {
            return false;
        }

        $this->dbConnection = null;

        return true;
    }
}
