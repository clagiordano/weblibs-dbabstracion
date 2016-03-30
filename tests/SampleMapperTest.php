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
    /** @var SampleMapper $mapper */
    private $mapper = null;
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

        $mapperOptions = [
            'entityTable' => 'tab_products',
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleMapper',
            $this->mapper
        );
    }

    /**
     *
     */
    public function testGetAdapter()
    {
        $this->assertEquals(
            $this->adapter,
            $this->mapper->getAdapter()
        );
    }

    /**
     *
     */
    public function testGetEntityClass()
    {
        $className = $this->mapper->getEntityClass();
        $this->assertInternalType('string', $className);
        $this->assertTrue(class_exists($className));
    }

    /**
     *
     */
    public function testGetEntityTable()
    {
        $tableName = $this->mapper->getEntityTable();
        $this->assertInternalType('string', $tableName);
    }

    public function testFind()
    {
        $entities = $this->mapper->find();

        if (count($entities) > 0) {
            $this->assertInstanceOf(
                $this->mapper->getEntityClass(),
                $entities[0]
            );
        }
    }

    public function testFindById()
    {
        $testId = 1;
        $entity = $this->mapper->findById($testId);

        $this->assertInstanceOf(
            $this->mapper->getEntityClass(),
            $entity
        );

        $this->assertEquals(
            $testId,
            $entity->id
        );

        $entity2 = $this->mapper->findById(9999);

        $this->assertNull(
            $entity2
        );
    }

    public function testSetInvalidEntityTable()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The entity table is invalid.');
        $this->mapper->setEntityTable(null);
    }

    public function testSetInvalidEntityTable2()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('The entity table is invalid.');
        $this->mapper->setEntityTable("");
    }

    public function testSetEntityTable()
    {
        $oldTableName = $this->mapper->getEntityTable();
        $this->assertInternalType('string', $oldTableName);

        $newTableName = uniqid("tab_");
        $this->mapper->setEntityTable($newTableName);

        $this->assertEquals(
            $newTableName,
            $this->mapper->getEntityTable()
        );

        // Reset previous table name
        $this->mapper->setEntityTable($oldTableName);
        $this->assertEquals(
            $oldTableName,
            $this->mapper->getEntityTable()
        );
    }

    public function testFullConstructor()
    {
        $mapperOptions = [
            'entityTable' => 'tab_products',
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }

    public function testInvalidConstructor()
    {
        $this->expectException('RuntimeException');

        $mapperOptions = [
            'entityTable' => 'tab_products',
            'entityClass' => null
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }

    public function testInvalidConstructor2()
    {
        $this->expectException('RuntimeException');

        $mapperOptions = [
            'entityTable' => null,
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }

    public function testSetInvalidEntityClass()
    {
        $this->expectException('InvalidArgumentException');
        $this->mapper->setEntityClass('InvalidClass');
    }

    public function testInvalidInsert()
    {
        $this->expectException('InvalidArgumentException');
        $this->mapper->insert(null);
    }
}
