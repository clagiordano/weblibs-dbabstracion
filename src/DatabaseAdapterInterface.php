<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * @class \clagiordano\weblibs\dbabstraction\DatabaseAdapterInterface
 * @brief
 */
interface DatabaseAdapterInterface
{
    /**
     * @return mixed
     */
    public function connect();

    /**
     * @return mixed
     */
    public function disconnect();

    /**
     * @param $queryString
     * @return mixed
     */
    public function query($queryString);

    /**
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    public function getInsertId();

    /**
     * @return mixed
     */
    public function countRows();

    /**
     * @return mixed
     */
    public function getAffectedRows();
}
