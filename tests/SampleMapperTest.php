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

    public function setUp()
    {
        $this->adapter = new PDOAdapter('localhost', 'test', 'test', 'sample');

        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->adapter
        );

        $this->class = new SampleMapper($this->adapter);
    }

    public function testUsage()
    {

    }
}
