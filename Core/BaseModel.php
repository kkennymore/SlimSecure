<?php

namespace SlimSecure\Core;

use SlimSecure\Core\Database;
use SlimSecure\Core\Security;
use SlimSecure\Core\Exceptions;
use SlimSecure\Configs\Env;
use PDO;

/**
 * Class BaseModel
 *
 * Serves as the foundational model class for all model classes in the SlimSecure system.
 * It extends the Database class to utilize its connection methods for performing
 * common CRUD operations. This model facilitates a simplified and uniform interaction
 * with the database across various parts of the application.
 */
class BaseModel extends Database
{

    protected $select = '*';
    protected $whereCondiction;
    protected $orderBy = '';
    protected $limit;
    protected $groupBy = '';
    protected $transactionStarted = false;
    protected $data = [];
    protected $single = false;
    protected $tableName = [];
    protected $dbTransactionData;
    protected $insert;
    protected $update;
    protected $insertValue;
    protected $paramsValue;
    protected $commit = false;
    protected $updateData = [];
    protected $deleteData = [];

    protected $join = [];

    protected $query;
    protected $whereValue;

    protected $db;

    public static function query()
    {
        return new static();
    }

    /**
     * Create a new BaseModel instance.
     */
    public function __construct()
    {
        $this->db = parent::__construct();
    }

    /**
     * Set the select statement for the query.
     *
     * @param string $tableName The name of the table to select from
     * @param string $select The columns to select
     * @return self The current BaseModel instance
     */
    public function select(string $tableName = '', string $select = ''): self
    {
        if (!empty($tableName)) {
            $this->tableName = $tableName;
        }

        if (!empty($select)) {
            $this->select = Security::removeSpaces($select);
        }

        return $this;
    }

    /**
     * Set the insert statement for the query.
     *
     * @param string $tableName The name of the table to insert into
     * @param array $query The data to be inserted
     * @return self The current BaseModel instance
     */
    public function insert(string $tableName = '', array $query = []): self
    {
        if (!empty($tableName)) {
            $this->tableName = $tableName;
        }

        if (!empty($query)) {
            foreach ($query as $key => $val) {
                $this->insert[] = $key . ',';
                $this->paramsValue[] =  ":" . $key . "," . $val;
                $this->insertValue[] = "?,";
            }
        }

        return $this;
    }

    /**
     * Set the update statement for the query.
     *
     * @param string $tableName The name of the table to update
     * @param array $dataValues The data to be updated
     * @return self The current BaseModel instance
     */
    public function update(string $tableName = '', array $dataValues = [])
    {
        if (!empty(Security::removeSpaces($tableName)) && !empty($dataValues)) {
            $this->updateData = $dataValues;
            $this->tableName = $tableName;
        }

        return $this;
    }

    /**
     * Set the delete statement for the query.
     *
     * @param string $tableName The name of the table to delete from
     * @return self The current BaseModel instance
     */
    public function delete(string $tableName = ''): self
    {
        if (!empty(Security::removeSpaces($tableName))) {
            $this->deleteData = $tableName;
            $this->tableName = $tableName;
        }

        return $this;
    }

    /**
     * Get the data from the database.
     *
     * @return mixed The fetched data
     */
    public function get()
    {
        $this->single = false;
        return $this->executeSelectQuery();
    }

    /**
     * Get the first row of data from the database.
     *
     * @return mixed The first row of data
     */
    public function first()
    {
        $this->single = true;
        return $this->executeSelectQuery();
    }

    /**
     * Join tables in the query.
     *
     * @param array $joinTableNameNColumns The tables and columns to join
     * @param string $baseTable The base table name
     * @return self The current BaseModel instance
     */
    public function join(array $joinTableNameNColumns = [], string $baseTable = '')
    {
        if (empty($baseTable)) {
            $baseTable = $this->tableName;
        }

        if (!empty($joinTableNameNColumns)) {
            $join = [];

            foreach ($joinTableNameNColumns as $columnNameKey => $columnNameValue) {
                $join[] =  " JOIN {$columnNameKey} ON {$baseTable}.{$columnNameValue} = {$columnNameKey}.{$columnNameValue} ";
            }

            $this->join = $join;
        }

        return $this;
    }

    /**
     * Add a where clause to the query.
     *
     * @param string $condition The condition for the where clause
     * @param string|array $conditionValue The value(s) for the where clause
     * @return self The current BaseModel instance
     */
    public function where(string $condition = '', string | array $conditionValue = []): self
    {
        if (!empty($condition) && !empty($conditionValue)) {
            $this->whereCondiction = "WHERE {$condition}";
            $this->whereValue = $conditionValue;
        }

        return $this;
    }

    /**
     * Group the results by a column.
     *
     * @param string $columnName The column to group by
     * @return self The current BaseModel instance
     */
    public function groupBy(string $columnName): self
    {
        $this->groupBy = "GROUP BY $columnName";
        return $this;
    }

    /**
     * Order the results by column(s).
     *
     * @param string $columnNames The column(s) to order by
     * @param string $order The order direction (ASC or DESC)
     * @return self The current BaseModel instance
     */
    public function orderBy(string $columnNames = "", string $order = "DESC")
    {
        if (!empty($columnNames) && !empty($order)) {
            $this->orderBy = "ORDER BY {$columnNames} {$order}";
        }

        return $this;
    }

    /**
     * Insert data into the database transactionally.
     *
     * @param array|object $transactionData The transaction data to be inserted
     * @return self The current BaseModel instance
     * $transactionData = [
     * ['tableName'=> "users","user_meta"], 
     * ['data' => [
     *   ['userId'=>'787878979', 'name'=>'kenneth'], 
     *   ['ipAddress'=>'24.38.37.3']
     *  ]
     * ]
     */
    public function insertDbTransact(array | object $transactionData = [])
    {
        if (!empty($transactionData)) {
            $this->transactionStarted = true;
            $this->dbTransactionData['tableNames'] = $transactionData[0];
            $this->dbTransactionData['data'] = $transactionData[1];
            $this->dbTransactionData = array_combine($this->dbTransactionData['tableNames'], $this->dbTransactionData['data']);
        }

        return $this;
    }

    /**
     * Limit the number of results returned.
     *
     * @param int|string $offset The starting offset
     * @param int|string $num The number of results to return
     * @return self The current BaseModel instance
     */
    public function limit(int | string $offset = 0, int | string $num = null)
    {
        if (!empty($num)) {
            $this->limit = "LIMIT {$offset}, {$num}";
        }

        return $this;
    }

    /**
     * Save the data to the database.
     *
     * @return mixed The result of the save operation
     */
    public function save()
    {
        if (!empty($this->insert)) {
            return $this->executeInsertQuery();
        }

        if (!empty($this->dbTransactionData)) {
            return $this->executeInsertQuery();
        }

        if (!empty($this->updateData)) {
            return  $this->executeUpdateQuery();
        }

        if (!empty($this->deleteData)) {
            return  $this->executeDeleteQuery();
        }
    }

    /**
     * Execute the delete query.
     *
     * @return mixed The result of the delete operation
     */
    private function executeDeleteQuery()
    {
        try {
            $this->query = "DELETE FROM {$this->tableName} {$this->whereCondiction}";

            $statement = $this->dbPrepare($this->query);

            foreach ($this->whereValue as $key => $value) {
                $statement->bindValue(++$key, $value);
            }

            list($this->query, $this->tableName, $this->whereCondiction, $this->whereValue, $this->deleteData) = null;

            return $statement->execute();
        } catch (\Exception $e) {
            http_response_code(Env::SERVER_ERROR_METHOD);

            if (Env::LOG_ERROR == true) {
                Exceptions::exceptionHandler($e);
            }

            return false;
        }
    }

    /**
     * Execute the update query.
     *
     * @return mixed The result of the update operation
     */
    private function executeUpdateQuery()
    {
        try {
            $updateColumns = array_keys($this->updateData);
            $updateValues = array_values($this->updateData);
            $updateString = implode('=?, ', $updateColumns) . '=?';

            // Assuming $this->whereCondiction already contains the 'WHERE ...' clause.
            $this->query = "UPDATE {$this->tableName} SET {$updateString} {$this->whereCondiction}";

            $statement = $this->dbPrepare($this->query);

            // Bind update values.
            foreach ($updateValues as $key => $value) {
                // PDOStatement::bindValue() expects the first parameter to be 1-indexed.
                $statement->bindValue($key + 1, $value);
            }

            // Bind the where condition value(s).
            // If $this->whereValue is an array, you need to ensure it's handled correctly.
            if (is_array($this->whereValue)) {
                // If whereValue is an array, bind each value.
                // This assumes your where condition is properly prepared for multiple values.
                foreach ($this->whereValue as $k => $v) {
                    $statement->bindValue($k + 1 + count($updateValues), $v);
                }
            } else {
                // For a single where value.
                $statement->bindValue(count($updateValues) + 1, $this->whereValue);
            }
            return $statement->execute();
        } catch (\Exception $e) {
            http_response_code(Env::SERVER_ERROR_METHOD);

            if (Env::LOG_ERROR == true) {
                Exceptions::exceptionHandler($e);
            }

            // It's a good idea to return false or another indicator of failure here.
            return false;
        }
    }


    /**
     * Execute the select query.
     *
     * @return mixed The fetched data
     */
    private function executeSelectQuery()
    {
        try {
            if (!empty($this->select)) {
                if (empty($this->join)) {
                    $this->query = "SELECT {$this->select} FROM {$this->tableName} {$this->whereCondiction} {$this->orderBy} {$this->groupBy} {$this->limit}";
                } else {
                    $this->query = "SELECT {$this->select} FROM {$this->tableName}";

                    foreach ($this->join as $joinKey => $joinValue) {
                        $this->query .= " " . $joinValue;
                    }

                    $this->query .= " {$this->whereCondiction} {$this->orderBy} {$this->groupBy} {$this->limit}";
                }

                $stmt = $this->dbPrepare($this->query);

                foreach ($this->whereValue as $key => $value) {
                    $stmt->bindValue(++$key, (string)$value);
                }

                $stmt->execute();

                if ($this->single) {
                    return $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                }
            }
        } catch (\Exception $e) {
            http_response_code(Env::SERVER_ERROR_METHOD);

            if (Env::LOG_ERROR == true) {
                Exceptions::exceptionHandler($e);
            }

            return false;
        }
    }

    /**
     * Execute the insert query.
     *
     * @return mixed The result of the insert operation
     */
    private function executeInsertQuery()
    {
        if ($this->transactionStarted == true && !empty($this->dbTransactionData)) {
            try {
                $this->dbBeginTransaction();

                foreach ($this->dbTransactionData as $tables => $columns) {
                    $columnNames = implode(',', array_keys($columns));
                    $placeholders = implode(',', array_fill(0, count($columns), '?'));

                    $this->query = "INSERT INTO {$tables} ($columnNames) VALUES ($placeholders)";

                    $stmt = $this->dbPrepare($this->query);

                    $count = 1;

                    foreach ($columns as $rowKey => $rowValue) {
                        $stmt->bindValue($count++, $rowValue);
                    }

                    $stmt->execute();
                }

                $this->transactionStarted = false;
                $this->dbTransactionData = null;
                $this->query = null;

                return $this->dbCommit();
            } catch (\PDOException $e) {
                $this->dbRollBack();
                http_response_code(500);

                if (Env::LOG_ERROR == true) {
                    Exceptions::exceptionHandler($e);
                }

                return false;
            }
        }

        try {
            if (!empty($this->insert) && !empty($this->insertValue)) {
                $this->query = "INSERT INTO {$this->tableName}(" . trim(implode($this->insert), ',') . ") VALUES(" . trim(implode($this->insertValue), ',') . ")";

                $stmt = $this->dbPrepare($this->query);

                foreach ($this->paramsValue as $key => $value) {
                    $stmt->bindValue(++$key, explode(",", $value)[1]);
                }

                $this->query = null;

                return $stmt->execute();
            }
        } catch (\Exception $e) {
            http_response_code(500);

            if (Env::LOG_ERROR == true) {
                Exceptions::exceptionHandler($e);
            }

            return false;
        }
    }
}
