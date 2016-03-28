<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * Interface MapperInterface
 *
 * @package clagiordano\weblibs\dbabstraction
 */
interface MapperInterface
{
    /**
     * @param $entityId
     * @return mixed
     */
    public function findById($entityId);

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
