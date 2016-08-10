<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    
    /**
     * @dataProvider additionProvider
     */
    public function testAdd($a, $b, $expected)
    {
        $this->assertEquals($expected, $a + $b);
    }
    
    public function additionProvider()
    {
        return [
            [0, 0, 0],
            [0, 1, 1],
            [1, 0, 1],
            [1, 1, 2],
        ];
    }
    
    public function testCalculate()
    {
        $this->assertEquals(2, 1 + 1);
        $this->assertEquals(1, 2 - 1);
        $this->assertEquals(4, 2 * 2);
        $this->assertEquals(3, 6 / 2);
        $this->assertEquals(5, 2 * 2 + 1);
    }
}
