<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\PDOAdapter;
use clagiordano\weblibs\dbabstraction\testdata\SampleEntity;
use clagiordano\weblibs\dbabstraction\testdata\SampleMapper;

/**
 * Class SampleMapperTest
 *
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class SampleMapperTest extends \PHPUnit_Framework_TestCase
{
    /** @var SampleEntity $entity */
    private $entity = null;
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

    public function testInvalidUpdate()
    {
        $this->expectException('InvalidArgumentException');
        $this->mapper->update(null);
    }

    public function testInvalidDelete()
    {
        $this->expectException('InvalidArgumentException');
        $this->mapper->delete(null);
    }

    /**
     * Test entity validity
     * @group invalid
     */
    public function testInvalidDelete2()
    {
        $this->expectException('InvalidArgumentException');
        $this->mapper->delete(new \stdclass());
    }

    public function testInsert()
    {
        $this->entity = new SampleEntity(
            [
                'code' => 'sample',
                'description' => 'sample entity'
            ]
        );

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        $lastId = $this->mapper->insert($this->entity);
        $this->assertInternalType('integer', $lastId);
        $this->assertTrue($lastId > 0);
    }

    public function testUpdate()
    {
        $updateString = 'sample entity update';
        $this->entity = $this->mapper->find("code = 'sample'")[0];

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        $this->entity->description = $updateString;
        $this->mapper->update($this->entity);

        $this->entity = $this->mapper->findById($this->entity->id);
        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        $this->assertEquals(
            $updateString,
            $this->entity->description
        );
    }

    public function testDeleteById()
    {
        $this->entity = $this->mapper->find("code = 'sample'")[0];
        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        $this->mapper->delete($this->entity->id);
        $this->entity = $this->mapper->findById($this->entity->id);

        $this->assertNull($this->entity);
    }

    public function testDeleteByEntity()
    {
        $this->entity = new SampleEntity(
            [
                'code' => 'sample',
                'description' => 'sample entity'
            ]
        );

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        $lastId = $this->mapper->insert($this->entity);
        $this->assertInternalType('integer', $lastId);
        $this->assertTrue($lastId > 0);
        $this->entity->id = $lastId;

        $this->mapper->delete($this->entity);
        $this->entity = $this->mapper->findById($lastId);

        $this->assertNull($this->entity);
    }

}
