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

    /**
     * @brief
     * @return
     */
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

    /**
     * @brief
     * @return
     */
    public function testSelect()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );
    }

    /**
     * @brief
     * @return
     */
    public function testSelect2()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
    }

    /**
     * @brief
     * @return
     */
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

    /**
     * @brief
     * @return
     */
    public function testUpdate()
    {
        $affectedRows = $this->object->update(
            "tab_sample",
            [
                'text' => 'testUpdate',
                'description' => 'test update description'
            ],
            "text = 'testInsert'"
        );

        $this->assertInternalType('integer', $affectedRows);
        $this->assertEquals($affectedRows, $this->object->countRows());
        $this->assertEquals($affectedRows, $this->object->getAffectedRows());
    }

    /**
     * @brief
     * @return
     */
    public function testDelete()
    {
        $affectedRows = $this->object->delete(
            "tab_sample",
            "text = 'testUpdate'"
        );

        $this->assertInternalType('integer', $affectedRows);
        $this->assertEquals($affectedRows, $this->object->countRows());
        $this->assertEquals($affectedRows, $this->object->getAffectedRows());
    }
}
