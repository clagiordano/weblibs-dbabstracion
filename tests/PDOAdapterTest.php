<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\PDOAdapter;

/**
 * Class PDOAdapterTest
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class PDOAdapterTest extends \PHPUnit_Framework_TestCase
{
    /** @var PDOAdapter $object */
    private $object;

    public function setUp()
    {
        $this->object = new PDOAdapter('localhost', 'test', 'test', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );
    }

    public function testFailedConnection()
    {
        $this->object = new PDOAdapter('localhosta', 'invalid', 'invalid', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );

        $this->setExpectedException('InvalidArgumentException');
        $this->object->connect();
    }

    public function testQuery()
    {
        $resource = $this->object->query('SELECT * FROM tab_sample');
        $this->assertInstanceOf(
            'PDOStatement',
            $resource
        );

        print_r($this->object->fetch());
        print_r($this->object->fetch());
    }

    public function testSelect()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );
    }

    public function testSelect2()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
    }

    public function testInsert()
    {
        $lastId = $this->object->insert(
            "tab_sample",
            [
                'text' => 'testInsert',
                'description' => 'test description'
            ]
        );

        $this->assertInternalType('integer', $lastId);
        $this->assertTrue(($lastId > 0));
    }
}
