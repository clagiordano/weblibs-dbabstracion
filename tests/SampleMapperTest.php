<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\PDOAdapter;
use clagiordano\weblibs\dbabstraction\testdata\SampleMapper;

/**
 * Class SampleMapperTest
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class SampleMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var SampleMapper $class */
    private $class = null;
    /** @var PDOAdapter $adapter */
    private $adapter = null;

    /**
     *
     */
    public function setUp()
    {
        $this->adapter = new PDOAdapter('localhost', 'test', 'test', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->adapter
        );

        $this->class = new SampleMapper($this->adapter);
        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleMapper',
            $this->class
        );
    }

    /**
     *
     */
    public function testGetAdapter()
    {
        $this->assertEquals(
            $this->adapter,
            $this->class->getAdapter()
        );
    }

    /**
     *
     */
    public function testGetEntityClass()
    {
        $className = $this->class->getEntityClass();
        $this->assertInternalType('string', $className);
        $this->assertTrue(class_exists($className));
    }

    /**
     *
     */
    public function testGetEntityTable()
    {
        $tableName = $this->class->getEntityTable();
        $this->assertInternalType('string', $tableName);
    }

    public function testFind()
    {
        $entities = $this->class->find();

        if (count($entities) > 0) {
            $this->assertInstanceOf(
                $this->class->getEntityClass(),
                $entities[0]
            );
        }
    }

    public function testFindById()
    {
        $testId = 1;
        $entity = $this->class->findById($testId);

        $this->assertInstanceOf(
            $this->class->getEntityClass(),
            $entity
        );

        $this->assertEquals(
            $testId,
            $entity->id
        );

        $entity2 = $this->class->findById(9999);

        $this->assertNull(
            $entity2
        );
    }

    public function testSetInvalidEntityTable()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The entity table is invalid.');
        $this->class->setEntityTable(null);
    }

    public function testSetInvalidEntityTable2()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The entity table is invalid.');
        $this->class->setEntityTable("");
    }

    public function testSetEntityTable()
    {
        $oldTableName = $this->class->getEntityTable();
        $this->assertInternalType('string', $oldTableName);

        $newTableName = uniqid("tab_");
        $this->class->setEntityTable($newTableName);

        $this->assertEquals(
            $newTableName,
            $this->class->getEntityTable()
        );

        // Reset previous table name
        $this->class->setEntityTable($oldTableName);
        $this->assertEquals(
            $oldTableName,
            $this->class->getEntityTable()
        );
    }

    public function testFullConstructro()
    {
        $mapperOptions = [
            'entityTable' => 'tab_products',
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->class = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }
}
