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
        $this->object = new PDOAdapter('127.0.0.1', 'travis', '', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );
    }

    /**
     * @brief
     * @return
     */
    public function testFailedConnection()
    {
        $this->object = new PDOAdapter('localhosta', 'invalid', 'invalid', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );

        $this->setExpectedException('InvalidArgumentException');
        $this->object->connect();

        $this->assertFalse($this->object->hasExecutionStatus());
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

        while (($row = $this->object->fetch()) !== false) {
            $this->assertInternalType('array', $row);
        }

        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testPreparedStatement()
    {
        $resource = $this->object->query(
            'SELECT * FROM tab_sample WHERE id=:id',
            [':id' => 1]
        );
        $this->assertInstanceOf(
            'PDOStatement',
            $resource
        );

        while (($row = $this->object->fetch()) !== false) {
            $this->assertInternalType('array', $row);
        }

        $this->assertTrue($this->object->hasExecutionStatus());
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

        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testInvalidQuery()
    {
        $this->setExpectedException('RuntimeException');
        $countRows = $this->object->query(
            "SELECT * FROM INVALID_TABLE"
        );

        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertFalse($this->object->hasExecutionStatus());
    }

    public function testNullQuery()
    {
        $this->setExpectedException('InvalidArgumentException');
        $countRows = $this->object->query(null);

        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertFalse($this->object->hasExecutionStatus());
    }

    /**
     * @brief
     * @return
     */
    public function testSelectAndDisconnect()
    {
        $countRows = $this->object->select('tab_sample');
        $this->assertInternalType(
            'integer',
            $countRows
        );

        while (($row = $this->object->fetch()) !== false) {
            $this->assertInternalType('array', $row);
        }

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());

        $this->assertTrue($this->object->disconnect());
        $this->assertTrue($this->object->hasExecutionStatus());
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
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testSelectWithWhere()
    {
        $countRows = $this->object->select('tab_sample', "text = 'testInsert'");
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertTrue($countRows > 0);
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testSelectWithLimit()
    {
        $countRows = $this->object->select('tab_sample', null, null, null, 1);
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertTrue($countRows > 0);
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testSelectWithOffset()
    {
        $countRows = $this->object->select('tab_sample', null, null, null, 1, 1);
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertTrue($countRows > 0);
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testSelectWithOrder()
    {
        $countRows = $this->object->select('tab_sample', null, "*", "id");
        $this->assertInternalType(
            'integer',
            $countRows
        );

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testFetchAll()
    {
        $countRows = $this->object->select('tab_sample', null, null, null, 1, 1);
        $this->assertInternalType(
            'integer',
            $countRows
        );

        while (($row = $this->object->fetch()) !== false) {
            $this->assertInternalType('array', $row);
        }

        $this->assertInternalType('integer', $countRows);
        $this->assertEquals($countRows, $this->object->countRows());
        $this->assertEquals($countRows, $this->object->getAffectedRows());
        $this->assertTrue($countRows > 0);
        $this->assertTrue($this->object->hasExecutionStatus());
    }

    public function testFetchWithoutResultset()
    {
        $this->assertFalse($this->object->fetch());

        $this->assertEquals(0, $this->object->countRows());
        $this->assertEquals(0, $this->object->getAffectedRows());
        $this->assertFalse($this->object->hasExecutionStatus());
    }

    public function testFreeResultWithoutResultset()
    {
        $this->assertFalse($this->object->freeResult());

        $this->assertEquals(0, $this->object->countRows());
        $this->assertEquals(0, $this->object->getAffectedRows());
        $this->assertFalse($this->object->hasExecutionStatus());
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
        $this->assertTrue($this->object->hasExecutionStatus());
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
        $this->assertTrue($this->object->hasExecutionStatus());
    }
}
