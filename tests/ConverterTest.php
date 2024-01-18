<?php

use Izzle\IO\Utilities\DataConverter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testConvertToArray(): void
    {
        $o = new stdClass();
        $o->child = new stdClass();
        $o->child->name = 'child';
        $o->child->id = 4;
        $o->name = 'test';
        $o->id = 1;
    
        $result = DataConverter::convert($o);
        
        self::assertIsArray($result);
        self::assertIsNotObject($result);
        self::assertArrayHasKey('name', $result);
        self::assertArrayHasKey('id', $result);
        self::assertEquals(1, $result['id']);
        self::assertEquals('test', $result['name']);
        self::assertIsArray($result['child']);
        self::assertIsNotObject($result['child']);
        self::assertEquals(4, $result['child']['id']);
        self::assertEquals('child', $result['child']['name']);
    }
    
    public function testConvertToObject(): void
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
    
        self::assertIsObject($result);
        self::assertIsNotArray($result);
        
        self::assertObjectHasProperty('id', $result);
        self::assertEquals(1, $result->id);
        self::assertObjectHasProperty('name', $result);
        self::assertEquals('test', $result->name);
    
        self::assertIsObject($result->child);
        self::assertIsNotArray($result->child);
    
        self::assertObjectHasProperty('child', $result);
        self::assertObjectHasProperty('id', $result->child);
        self::assertEquals(4, $result->child->id);
        self::assertObjectHasProperty('name', $result->child);
        self::assertEquals('child', $result->child->name);
    }
}
