<?php

namespace clagiordano\weblibs\dbabstraction\testdata;

use clagiordano\weblibs\dbabstraction\AbstractMapper;

/**
 * Class SampleMapper
 */
class SampleMapper extends AbstractMapper
{
    protected $entityTable = 'tab_products';
    protected $entityClass = 'clagiordano\weblibs\dbabstraction\testdata\SampleEntity';

    /**
     * Sample insert method
     *
     * @param SampleEntity $entity
     * @return mixed
     */
    public function insert($entity)
    {
        if (!$entity instanceof SampleEntity) {
            throw new \InvalidArgumentException(
                __METHOD__ . ": Invalid entity type."
            );
        }

        return $this->adapter->insert($this->entityTable, $entity->toArray());
    }

    /**
     * Sample delete method
     *
     * @param mixed $id
     * @param string $column
     * @return mixed
     * @internal param \SampleEntity $entity
     */
    public function delete($id, $column = 'id')
    {
        if ($id instanceof SampleEntity) {
            $id = $id->id;
        }

        return $this->adapter->delete($this->entityTable, "{$column} = {$id}");
    }

    /**
     * Create a SampleEntity entity with the supplied data
     * @param array $fields
     * @return SampleEntity
     */
    protected function createEntity(array $fields)
    {
        return new SampleEntity(
            [
                'id' => $fields['id'],
                'code' => $fields['code'],
                'brand' => $fields['brand'],
                'model' => $fields['model'],
                'description' => $fields['description']
            ]
        );
    }
}
