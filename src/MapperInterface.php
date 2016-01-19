<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * @class \clagiordano\weblibs\dbabstraction\MapperInterface
 * @brief
 */
interface MapperInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param string $criteria
     * @return mixed
     */
    public function find($criteria = '');

    /**
     * @param $entity
     * @return mixed
     */
    public function insert($entity);

    /**
     * @param $entity
     * @return mixed
     */
    public function update($entity);

    /**
     * @param $entity
     * @return mixed
     */
    public function delete($entity);
}
