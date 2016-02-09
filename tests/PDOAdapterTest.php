<?php

namespace clagiordano\weblibs\dbabstraction\tests;

use clagiordano\weblibs\dbabstraction\PDOAdapter;

class PDOAdapterTest extends \PHPUnit_Framework_TestCase
{
    private $object;
    
    public function setUp()
    {
        $this->object = new PDOAdapter("localhost", "test", "test", "sample");
        
        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );
    }
    
    public function testOne()
    {
        $resource = $this->object->query("SELECT * FROM sample");
        
       var_export($resource);
    }
}
