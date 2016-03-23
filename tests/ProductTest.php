<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\ProductMapper;

/**
 * Class PDOAdapterTest
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class ProductTest extends \PHPUnit_Framework_TestCase
{
    /** @var ProductMapper $object */
    private $object;

    public function setUp()
    {
        $this->object = new ProductMapper('localhost', 'test', 'test', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );
    }

    public function testFetchClassInvalidTable()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertTrue($this->object->hasExecutionStatus());

        $row = $this->object->fetch();
        //var_dump($row);
        $this->assertInstanceOf('clagiordano\weblibs\dbabstraction\ProductEntity', $row);
    }

    public function testFetchClass()
    {
        $countRows = $this->object->select('tab_products');
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertTrue($this->object->hasExecutionStatus());

        $row = $this->object->fetch();
        //var_dump($row);
        $this->assertInstanceOf('clagiordano\weblibs\dbabstraction\ProductEntity', $row);
    }
}
