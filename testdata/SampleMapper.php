<?php

namespace clagiordano\weblibs\dbabstraction\testdata;

use clagiordano\weblibs\dbabstraction\AbstractMapper;

/**
 * Class SampleMapper
 *
 * @package clagiordano\weblibs\dbabstraction\testdata
 */
class SampleMapper extends AbstractMapper
{
    protected $entityTable = null;
    protected $entityClass = null;

    /**
     * Create a SampleEntity entity with the supplied data
     *
     * @param array $fields
     * @return SampleEntity
     */
    protected function createEntity(array $fields)
    {
        return new SampleEntity(
            [
                'id' => $fields['id'],
                'brand' => $fields['brand'],
                'code' => $fields['code'],
                'description' => $fields['description'],
            ]
        );
    }
}
