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
        unset($limit);
        unset($offset);

        // Real query
        $queryString = parent::buildSelect($table, $conditions, $fields, $order);

        // Support query to detect total results
        $queryTotal = parent::buildSelect($table, $conditions, "COUNT(*) AS countTotal", $order);
        $this->totalResults = $this->query($queryTotal)->fetch()['countTotal'];

        return $queryString;
    }

    public function getPage($selectPage = 'first')
    {
        // $this->
    }

    protected function calculateRange()
    {
        switch ($Page) {
            case "all":
                // no limit;
                break;
            case "first":
                $start = 0;
                $order .= " LIMIT $start, " . $PageLimit;
                break;
            case "last":
                $start = (intval($TotResults / $PageLimit) * $PageLimit);
                $order .= " LIMIT $start, " . $PageLimit;
                break;
            case "next":
                if (($_SESSION['start'] + $PageLimit) > $TotResults) {
                    $start = (intval($TotResults / $PageLimit) * $PageLimit);
                } else {
                    $start = ($_SESSION['start'] + $PageLimit);
                }
                $order .= " LIMIT $start, " . $PageLimit;
                break;
            case "prev":
                if (($_SESSION['start'] - $PageLimit) < 0) {
                    $start = 0;
                } else {
                    $start = ($_SESSION['start'] - $PageLimit);
                }
                $order .= " LIMIT $start, " . $PageLimit;
                break;
        }
    }
}
