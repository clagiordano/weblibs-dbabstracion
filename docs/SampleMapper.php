<?php

use clagiordano\weblibs\dbabstraction\AbstractMapper;

/**
 * Class SampleMapper
 */
class SampleMapper extends AbstractMapper
{
    protected $entityTable = 'comments';

    /**
     * Insert a new comment
     */
    public function insert(SampleEntity $comment)
    {
        return $this->adapter->insert($this->entityTable, $comment->toArray());
    }

    /**
     * Delete an existing comment
     * @param $entityId
     * @param string $col
     * @return mixed
     */
    public function delete($entityId, $col = 'id')
    {
        if ($entityId instanceof SampleEntity) {
            $entityId = $entityId->id;
        }
        return $this->adapter->delete($this->entityTable, "$col = $entityId");
    }

    /**
     * Create a comment entity with the supplied data
     * @param array $fields
     * @return SampleEntity
     */
    protected function createEntity(array $fields)
    {
        return new SampleEntity(
            [
                'id' => $fields['id'],
                'content' => $fields['content'],
                'author' => $fields['author']
            ]
        );
    }
}

