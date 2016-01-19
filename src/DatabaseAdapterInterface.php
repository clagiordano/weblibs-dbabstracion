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
    
    public function query($queryString);
    
    public function fetch();
    
    public function select($table, $conditions = '', $fields = '*', $order = '', $limit = null, $offset = null);
    
    public function insert($table, array $data);
    
    public function update($table, array $data, $conditions);
    
    public function delete($table, $conditions);
    
    public function getInsertId();
    
    public function countRows();
    
    public function getAffectedRows();
}
