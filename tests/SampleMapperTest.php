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
    /** @var integer $lastId */
    protected static $lastId;

    /**
     *
     */
    public function setUp()
    {
        $this->adapter = new PDOAdapter('127.0.0.1', 'travis', '', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->adapter
        );

        $mapperOptions = [
            'entityTable' => 'tab_sample',
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );

        $this->assertInstanceOf(
            '\clagiordano\weblibs\dbabstraction\testdata\SampleMapper',
            $this->mapper
        );

        $this->assertEquals(
            'tab_sample',
            $this->mapper->getEntityTable()
        );

        $this->assertEquals(
            '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->mapper->getEntityClass()
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

    public function testSetInvalidEntityTable()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The entity table is invalid.'
        );
        $this->mapper->setEntityTable(null);
    }

    public function testSetInvalidEntityTable2()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'The entity table is invalid.'
        );
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
            'entityTable' => 'tab_sample',
            'entityClass' => '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity'
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }

    public function testInvalidConstructor()
    {
        $this->setExpectedException('RuntimeException');

        $mapperOptions = [
            'entityTable' => 'tab_sample',
            'entityClass' => null
        ];

        $this->mapper = new SampleMapper(
            $this->adapter,
            $mapperOptions
        );
    }

    public function testInvalidConstructor2()
    {
        $this->setExpectedException('RuntimeException');

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
        $this->setExpectedException('InvalidArgumentException');
        $this->mapper->setEntityClass('InvalidClass');
    }

    public function testInvalidInsert()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mapper->insert(null);
    }

    public function testInvalidUpdate()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mapper->update(null);
    }

    public function testInvalidDelete()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mapper->delete(null);
    }

    /**
     * Test entity validity
     * @group invalid
     */
    public function testInvalidDelete2()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->mapper->delete(new \stdClass());
    }

    public function testInsert()
    {
        self::$lastId = null;
        $this->entity = new SampleEntity(
            [
                'text' => 'sample',
                'description' => 'sample entity'
            ]
        );

        $this->assertInstanceOf(
            '\clagiordano\weblibs\dbabstraction\testdata\SampleEntity',
            $this->entity
        );

        self::$lastId = $this->mapper->insert($this->entity);
        $this->assertInternalType('integer', self::$lastId);
        $this->assertTrue(self::$lastId > 0);
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
        $entity = $this->mapper->findById(self::$lastId);

        $this->assertInstanceOf(
            $this->mapper->getEntityClass(),
            $entity
        );

        $this->assertEquals(
            self::$lastId,
            $entity->id
        );

        $entity2 = $this->mapper->findById(9999);

        $this->assertNull(
            $entity2
        );
    }

    public function testUpdate()
    {
        $updateString = 'sample entity update';
        $this->entity = $this->mapper->find("text = 'sample'")[0];

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
        $this->entity = $this->mapper->find("text = 'sample'")[0];
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
                'text' => 'sample',
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
