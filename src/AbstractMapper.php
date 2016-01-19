<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * @class \clagiordano\weblibs\dbabstraction\AbstractMapper
 * @brief
 */
abstract class AbstractMapper implements MapperInterface
{
    protected $adapter;
    protected $entityTable;
    protected $entityClass;

    /**
     * Constructor
     * @param DatabaseAdapterInterface $adapter
     * @param array $entityOptions
     */
    public function __construct(DatabaseAdapterInterface $adapter, array $entityOptions = [])
    {
        $this->adapter = $adapter;
        // set the entity table is the option has been specified
        if (isset($entityOptions['entityTable'])) {
            $this->setEntityTable($entityOptions['entityTable']);
        }

        // set the entity class is the option has been specified
        if (isset($entityOptions['entityClass'])) {
            $this->setEntityClass($entityOptions['entityClass']);
        }

        // check the entity options
        $this->checkEntityOptions();
    }

    /**
     * Check if the entity options have been set
     */
    protected function checkEntityOptions()
    {
        if (!isset($this->entityTable)) {
            throw new \RuntimeException('The entity table has not been set yet.');
        }
        if (!isset($this->entityClass)) {
            throw new \RuntimeException('The entity class has been not set yet.');
        }
    }

    /**
     * Get the database adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get the entity table
     */
    public function getEntityTable()
    {
        return $this->entityTable;
    }

    /**
     * Set the entity table
     * @param $entityTable
     * @return $this
     */
    public function setEntityTable($entityTable)
    {
        if (!is_string($entityTable) || empty($entityTable)) {
            throw new \InvalidArgumentException('The entity table is invalid.');
        }
        $this->entityTable = $entityTable;

        return $this;
    }

    /**
     * Get the entity class
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * Set the entity class
     * @param $entityClass
     * @return $this
     */
    public function setEntityClass($entityClass)
    {
        if (!is_subclass_of($entityClass, 'BlogModelAbstractEntity')) {
            throw new \InvalidArgumentException('The entity class is invalid.');
        }
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * Find an entity by its ID
     * @param $id
     * @return mixed|null
     */
    public function findById($id)
    {
        $this->adapter->select($this->entityTable, "id = {$id}");

        if ($data = $this->adapter->fetch()) {
            return $this->createEntity($data);
        }

        return null;
    }

    /**
     * Reconstitute an entity with the data retrieved from
     * the storage (implementation delegated to concrete mappers)
     * @param array $data
     * @return
     */
    abstract protected function createEntity(array $data);

    /**
     * Find entities according to the given criteria
     * (all entities will be fetched if no criteria are specified)
     *
     * @param string $conditions
     * @return array|CollectionEntityCollection|mixed
     */
    public function find($conditions = '')
    {
        $collection = new CollectionEntityCollection;
        $this->adapter->select($this->entityTable, $conditions);
        while ($data = $this->adapter->fetch()) {
            $collection[] = $this->createEntity($data);
        }
        return $collection;
    }

    /**
     * Insert a new entity in the storage
     * @param $entity
     * @return mixed
     */
    public function insert($entity)
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException(
                'The entity to be inserted must be an instance of ' . $this->entityClass . '.'
            );
        }
        return $this->adapter->insert($this->entityTable, $entity->toArray());
    }

    /**
     * Update an existing entity in the storage
     * @param $entity
     * @return mixed
     */
    public function update($entity)
    {
        if (!$entity instanceof $this->entityClass) {
            throw new \InvalidArgumentException(
                'The entity to be updated must be an instance of ' . $this->entityClass . '.'
            );
        }
        $id = $entity->id;
        $data = $entity->toArray();
        unset($data['id']);
        return $this->adapter->update($this->entityTable, $data, "id = $id");
    }

    /**
     * Delete one or more entities from the storage
     * @param $id
     * @param string $col
     * @return mixed
     */
    public function delete($id, $col = 'id')
    {
        if ($id instanceof $this->entityClass) {
            $id = $id->id;
        }
        return $this->adapter->delete($this->entityTable, "$col = $id");
    }
}
