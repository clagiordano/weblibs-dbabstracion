<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\Pager;

/**
 * Class PagerTest
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class PagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var PDOAdapter $object */
    private $object;

    public function setUp()
    {
        $this->object = new Pager('127.0.0.1', 'travis', '', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\Pager',
            $this->object
        );
    }

    public function testSelect()
    {
        $resource = $this->object->select('tab_products');
        var_dump($resource);
    }
}
