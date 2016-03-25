<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\testdata\SampleEntity;

/**
 * Class SampleEntityTest
 * @package clagiordano\weblibs\dbabstraction\tests
 */
class SampleEntityTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->class = new SampleEntity([]);
    }

    public function testUsage()
    {

    }
}
