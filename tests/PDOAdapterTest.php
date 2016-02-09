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
    
    public function testFailedConnection()
    {
        $this->object = new PDOAdapter("localhosta", "invalid", "invalid", "sample");
        
        $this->assertInstanceOf(
            'clagiordano\weblibs\dbabstraction\PDOAdapter',
            $this->object
        );
        
        $this->setExpectedException('InvalidArgumentException');
        $this->object->connect();
    }
    
    public function testOne()
    {
        $resource = $this->object->query("SELECT * FROM tab_sample");
        $this->assertInstanceOf(
            'PDOStatement',
            $resource
        );
        
        print_r($this->object->fetch());
        print_r($this->object->fetch());
    }
}
