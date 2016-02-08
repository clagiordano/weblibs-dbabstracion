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
        $result = $this->object->query("SELECT * FROM users");
        
       var_export($result);
    }
}
