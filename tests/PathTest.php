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
        self::assertTrue(Path::hasExtension('test.html'));
    }
    
    /**
     * Expect False
     */
    public function testHasExtensionFalse()
    {
        self::assertFalse(Path::hasExtension('/html/test/this/test'));
    }
    
    /**
     * Expect False
     */
    public function testHasExtensionFalseEmptyInput()
    {
        self::assertFalse(Path::hasExtension(''));
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetExtensionIsEmpty()
    {
        $tmp = Path::getExtension('/html/test/this/test');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = 'html
     */
    public function testGetExtension()
    {
        $tmp = Path::getExtension('/html/test/this/test.html');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('html', $tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetExtensionEmptyInput()
    {
        $tmp = Path::getExtension('');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetFileNameWithoutExtensionWithoutFilename()
    {
        $tmp = Path::getFileNameWithoutExtension('/html/test/this/.html');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = 'test'
     */
    public function testGetFileNameWithoutExtension()
    {
        $tmp = Path::getFileNameWithoutExtension('/html/test/this/test.html');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('test', $tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetFileNameWithoutExtensionEmptyInput()
    {
        $tmp = Path::getFileNameWithoutExtension('');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string 'test.html'
     */
    public function testGetFileNameWithExtension()
    {
        self::assertEquals('test.html', Path::getFileName('/html/test/this/test.html'));
    }
    
    /**
     * Expect string 'test'
     */
    public function testGetFileNameWithNoExtension()
    {
        self::assertEquals('test', Path::getFileName('/html/test/this/test'));
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetFileNameEmptyInput()
    {
        $tmp = Path::getFileName('');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '/html/test/this'
     */
    public function testGetDirectoryName()
    {
        $tmp = Path::getDirectoryName('/html/test/this/test.html');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('/html/test/this', $tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '.'
     */
    public function testGetDirectoryNameWithoutDirectory()
    {
        $tmp = Path::getDirectoryName('test.html');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('.', $tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetDirectoryNameEmptyInput()
    {
        $tmp = Path::getDirectoryName('');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '/html/test/this'
     */
    public function testGetDirectoryNameWithSecondParam()
    {
        $tmp = Path::getDirectoryName('/html/test/this/test.html', false);
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('/html/test/this', $tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '.'
     */
    public function testGetDirectoryNameWithoutDirectoryWithSecondParam()
    {
        $tmp = Path::getDirectoryName('test.html', false);
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('.', $tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testGetDirectoryNameEmptyInputWithSecondParam()
    {
        $tmp = Path::getDirectoryName('',false);
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '/html/test/this/test/test.php'
     */
    public function testCombine()
    {
        $tmp = Path::combine('/html/test/this/test', 'test.php');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('/html/test/this/test/test.php', $tmp);
    }
    
    /**
     * Expect string length greater as 0
     * and string = '/html/test/this/test/ /Schinken'
     */
    public function testCombineThreeArgs()
    {
        $tmp = Path::combine('/html/test/this/test', ' ', 'Schinken');
        self::assertGreaterThan(0, strlen($tmp));
        self::assertEquals('/html/test/this/test/ /Schinken', $tmp);
    }
    
    /**
     * Expect string length of 0
     */
    public function testCombineEmptyInput()
    {
        $tmp = Path::combine('');
        self::assertEquals(0, strlen($tmp));
        self::assertEmpty($tmp);
    }
    
    public function testCombineWrongInputType()
    {
        $this->expectException(InvalidArgumentException::class);
        Path::combine();
    }
}
