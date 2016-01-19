<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * @class \clagiordano\weblibs\dbabstraction\MapperInterface
 * @brief
 */
interface MapperInterface
{
    public function findById($id);
    
    public function find($criteria = '');

    public function insert($entity);

    public function update($entity);

    public function delete($entity);
}
