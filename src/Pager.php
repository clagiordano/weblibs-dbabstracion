<?php

namespace clagiordano\weblibs\dbabstraction;

/**
 * Response class for output/return formatted JSON data from array
 *
 * @package clagiordano\weblibs\dbabstraction
 */
class Pager extends PDOAdapter
{
    /** @var int $pageLimit */
    protected $pageLimit = 20;
    /** @var string $queryStatement */
    protected $queryStatement = null;
    /** @var int $totalResults */
    protected $totalResults = null;

    public function setPageLimit($pageLimit)
    {
        $this->pageLimit = (int)$pageLimit;
    }

    public function getPageLimit()
    {
        return $this->pageLimit;
    }

    public function buildSelect(
        $table,
        $conditions = null,
        $fields = null,
        $order = null,
        $limit = null,
        $offset = null
    ) {
        $queryString = parent::buildSelect($table, $conditions, $fields, $order);
        $queryTotal = parent::buildSelect($table, $conditions, "COUNT(*) AS countTotal", $order);
        var_dump($queryTotal);
        $this->totalResults = $this->query($queryTotal)->fetch()['countTotal'];

        return $queryString;
    }

    public function getPage() {
        // $this->
    }
}
