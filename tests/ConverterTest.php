<?php

use Izzle\IO\Utilities\DataConverter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testConvertToArray()
    {
        $o = new \stdClass();
        $o->child = new \stdClass();
        $o->child->name = 'child';
        $o->child->id = 4;
        $o->name = 'test';
        $o->id = 1;
    
        $result = DataConverter::convert($o);
        
        $this->assertTrue(is_array($result));
        $this->assertFalse(is_object($result));
        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertEquals(1, $result['id']);
        $this->assertEquals('test', $result['name']);
        $this->assertTrue(is_array($result['child']));
        $this->assertFalse(is_object($result['child']));
        $this->assertEquals(4, $result['child']['id']);
        $this->assertEquals('child', $result['child']['name']);
    }
    
    public function testConvertToObject()
    {
        $arr = [
            'name' => 'test',
            'id' => 1,
            'child' => [
                'name' => 'child',
                'id' => 4
            ]
        ];
        
        $result = DataConverter::convert($arr);
    
        $this->assertTrue(is_object($result));
        $this->assertFalse(is_array($result));
        $this->assertAttributeEquals(1, 'id', $result);
        $this->assertAttributeEquals('test', 'name', $result);
    
        $this->assertTrue(is_object($result->child));
        $this->assertFalse(is_array($result->child));
        $this->assertAttributeEquals(4, 'id', $result->child);
        $this->assertAttributeEquals('child', 'name', $result->child);
    }
}
