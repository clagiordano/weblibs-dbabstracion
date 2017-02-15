<?php

namespace clagiordano\weblibs\dbabstraction;

/**
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
    protected $totalResults = 0;

    /**
     * @param $pageLimit
     */
    public function setPageLimit($pageLimit)
    {
        $this->pageLimit = (int)$pageLimit;
    }

    /**
     * @return int
     */
    public function getPageLimit()
    {
        return $this->pageLimit;
    }

    /**
     * Build a select statement from params
     *
     * @param string $table
     * @param string $conditions
     * @param string $fields
     * @param string $order
     * @param string $limit
     * @param string $offset
     * @return string
     */
    public function buildSelect(
        $table,
        $conditions = null,
        $fields = null,
        $order = null,
        $limit = null,
        $offset = null
    ) {
        unset($limit);
        unset($offset);

        // Real query
        $queryString = parent::buildSelect($table, $conditions, $fields, $order);

        // Support query to detect total results
        $queryTotal = parent::buildSelect($table, $conditions, "COUNT(*) AS countTotal", $order);
        $this->totalResults = $this->query($queryTotal)->fetch()['countTotal'];

        return $queryString;
    }

    /**
     * @param string $selectPage
     */
    public function getPage($selectPage = 'first')
    {
        // $this->
    }

    /**
     * @param string $selectPage
     */
    protected function calculateRange($selectPage = 'first')
    {
        switch ($selectPage) {
            case "all":
                // no limit;
                break;

            default:
            case "first":
                $start = 0;
                $order .= " LIMIT $start, " . $this->getPageLimit();
                break;

            case "last":
                $start = (intval($this->totalResults / $this->getPageLimit()) * $this->getPageLimit());
                $order .= " LIMIT $start, " . $this->getPageLimit();
                break;

            case "next":
                if (($_SESSION['start'] + $this->getPageLimit()) > $this->totalResults) {
                    $start = (intval($this->totalResults / $this->getPageLimit()) * $this->getPageLimit());
                } else {
                    $start = ($_SESSION['start'] + $this->getPageLimit());
                }
                $order .= " LIMIT $start, " . $this->getPageLimit();
                break;

            case "prev":
                if (($_SESSION['start'] - $this->getPageLimit()) < 0) {
                    $start = 0;
                } else {
                    $start = ($_SESSION['start'] - $this->getPageLimit());
                }
                $order .= " LIMIT $start, " . $this->getPageLimit();
                break;
        }
    }
}
