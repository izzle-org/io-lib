<?php

use Izzle\IO\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    
    /**
     * Expect True
     */
    public function testHasExtensionTrue()
    {
        $this->assertTrue(Path::hasExtension('test.html'));
    }
    
    /**
     * Expect False
     */
    public function testHasExtensionFalse()
    {
        $this->assertFalse(Path::hasExtension('/html/test/this/test'));
    }
    
    /**
     * Expect False
     */
    public function testHasExtensionFalseEmptyInput()
    {
        $this->assertFalse(Path::hasExtension(''));
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetExtensionIsEmpty()
    {
        $tmp = Path::getExtension('/html/test/this/test');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = 'html
     */
    public function testGetExtension()
    {
        $tmp = Path::getExtension('/html/test/this/test.html');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('html', $tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetExtensionEmptyInput()
    {
        $tmp = Path::getExtension('');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetFileNameWithoutExtensionWithoutFilename()
    {
        $tmp = Path::getFileNameWithoutExtension('/html/test/this/.html');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = 'test'
     */
    public function testGetFileNameWithoutExtension()
    {
        $tmp = Path::getFileNameWithoutExtension('/html/test/this/test.html');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('test', $tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetFileNameWithoutExtensionEmptyInput()
    {
        $tmp = Path::getFileNameWithoutExtension('');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string 'test.html'
     */
    public function testGetFileNameWithExtension()
    {
        $this->assertEquals('test.html', Path::getFileName('/html/test/this/test.html'));
    }
    
    /**
     * Expect string 'test'
     */
    public function testGetFileNameWithNoExtension()
    {
        $this->assertEquals('test', Path::getFileName('/html/test/this/test'));
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetFileNameEmptyInput()
    {
        $tmp = Path::getFileName('');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '/html/test/this'
     */
    public function testGetDirectoryName()
    {
        $tmp = Path::getDirectoryName('/html/test/this/test.html');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('/html/test/this', $tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '.'
     */
    public function testGetDirectoryNameWithoutDirectory()
    {
        $tmp = Path::getDirectoryName('test.html');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('.', $tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetDirectoryNameEmptyInput()
    {
        $tmp = Path::getDirectoryName('');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '/html/test/this'
     */
    public function testGetDirectoryNameWithSecondParam()
    {
        $tmp = Path::getDirectoryName('/html/test/this/test.html', false);
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('/html/test/this', $tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '.'
     */
    public function testGetDirectoryNameWithoutDirectoryWithSecondParam()
    {
        $tmp = Path::getDirectoryName('test.html', false);
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('.', $tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testGetDirectoryNameEmptyInputWithSecondParam()
    {
        $tmp = Path::getDirectoryName('',false);
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '/html/test/this/test/test.php'
     */
    public function testCombine()
    {
        $tmp = Path::combine('/html/test/this/test', 'test.php');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('/html/test/this/test/test.php', $tmp);
    }
    
    /**
     * Expect string lenght greater as 0
     * and string = '/html/test/this/test/ /Schinken'
     */
    public function testCombineThreeArgs()
    {
        $tmp = Path::combine('/html/test/this/test', ' ', 'Schinken');
        $this->assertGreaterThan(0, strlen($tmp));
        $this->assertEquals('/html/test/this/test/ /Schinken', $tmp);
    }
    
    /**
     * Expect string lenght of 0
     */
    public function testCombineEmptyInput()
    {
        $tmp = Path::combine('');
        $this->assertEquals(0, strlen($tmp));
        $this->assertEmpty($tmp);
    }
    
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCombineWrongInputType()
    {
        $tmp = Path::combine();
    }
}
