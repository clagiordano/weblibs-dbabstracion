<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * @class \clagiordano\weblibs\dbabstraction\DatabaseAdapterInterface
 * @brief
 */
interface DatabaseAdapterInterface
{
    public function connect();

    public function disconnect();

    /**
     * @param $queryString
     * @return mixed
     */
    public function query($queryString);

    public function fetch();

    /**
     * @param $table
     * @param string $conditions
     * @param string $fields
     * @param string $order
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null);

    /**
     * @param $table
     * @param array $data
     * @return mixed
     */
    public function insert($table, array $data);

    /**
     * @param $table
     * @param array $data
     * @param $conditions
     * @return mixed
     */
    public function update($table, array $data, $conditions);

    /**
     * @param $table
     * @param $conditions
     * @return mixed
     */
    public function delete($table, $conditions);

    public function getInsertId();

    public function countRows();

    public function getAffectedRows();
}
