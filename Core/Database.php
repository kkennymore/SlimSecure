<?php

namespace SlimSecure\Core;
/**
 * Author: Oaad Global
 * Developer: Hitek Financials Ltd
 * Year: 2024
 * Developer Contact: contact@tekfinancials.ng, kennethusiobaifo@yahoo.com
 * Project Name: Slimez
 * Description: Slimez.
 */

use SlimSecure\Configs\Env;
use PDO;

/**
 * Class Database
 *
 * This abstract class provides a database connection and common database operations using PDO.
 */
abstract class Database
{
    protected $connection;

    /**
     * Constructor - Establishes a database connection using PDO.
     *
     * @throws \Exception if the connection to the database fails
     */
    public function __construct()
    {
        $this->connection = new PDO(Env::DB_DRIVER . ":host=" . Env::DB_HOST . ";dbname=" . Env::DB_NAME, Env::DB_USER, Env::DB_PASSWORD);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->query("SET NAMES " . Env::DB_CHARSET);
        if (!$this->connection) {
            throw new \Exception('Could not connect to the database');
        }
    }

    /**
     * Prepare a PDO statement.
     *
     * @param string $sql The SQL query
     * @return mixed The prepared statement, or false if the connection is not established
     */
    public function dbPrepare($sql)
    {
        if (!$this->connection) {
            return false;
        }
        $result = $this->connection->prepare($sql);
        return $result;
    }

    /**
     * Begin a PDO transaction.
     *
     * @return bool True if the transaction is successfully started, or false if the connection is not established
     */
    public function dbBeginTransaction()
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->beginTransaction();
    }

    /**
     * Commit a PDO transaction.
     *
     * @return bool True if the transaction is successfully committed, or false if the connection is not established
     */
    public function dbCommit()
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->commit();
    }

    /**
     * Roll back a PDO transaction.
     *
     * @return bool True if the transaction is successfully rolled back, or false if the connection is not established
     */
    public function dbRollBack()
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->rollBack();
    }

    /**
     * Get the ID of the last inserted row or sequence value.
     *
     * @return mixed The last inserted ID, or false if the connection is not established
     */
    public function dbLastInsertId()
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->lastInsertId();
    }

    /**
     * Check if a transaction is currently active.
     *
     * @return bool True if a transaction is active, false if not or if the connection is not established
     */
    public function dbInTransaction()
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->inTransaction();
    }

    /**
     * Get the available PDO drivers.
     *
     * @return array|false An array of available PDO drivers, or false if the connection is not established
     */
    public function dbGetAvailableDrivers(): array
    {
        if (!$this->connection) {
            return false;
        }
        return $this->connection->getAvailableDrivers();
    }
}
