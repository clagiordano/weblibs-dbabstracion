<?php

namespace clagiordano\weblibs\dbabstraction;

use ProductEntity;

class ProductMapper extends PDOAdapter
{
    public function fetch()
    {
        $this->resourceHandle->setFetchMode(
            \PDO::FETCH_CLASS,
            'clagiordano\weblibs\dbabstraction\ProductEntity'
        );

        if ($this->resourceHandle !== null) {
            if (($row = $this->resourceHandle->fetch(\PDO::FETCH_CLASS)) === false) {
                $this->freeResult();
            }

            return $row;
        }

        return false;
    }
}
