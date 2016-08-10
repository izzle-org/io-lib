<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class FileInfoTest extends TestCase
{
    public function testFileInfoConstructorWithFullpath()
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml');
        $this->assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithFullpathAndSecondParam()
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml', false);
        $this->assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectoryAndSecondParam()
    {
        $file = new FileInfo('homepage.xml', false);
        $this->assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectory()
    {
        $file = new FileInfo('homepage.xml');
        $this->assertInstanceOf(FileInfo::class, $file);
    }
    
    /**
     * @expectedException Izzle\IO\Exception\ArgumentNullException
     * @expectedExceptionMessage path is null
     */
    public function testFileInfoConstructorWithNoPath()
    {
        $file = new FileInfo(null);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath1()
    {
        $file = new FileInfo('/html/case/test/>this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath2()
    {
        $file = new FileInfo('/html/case/test/,this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath3()
    {
        $file = new FileInfo('/html/case/test/<this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath4()
    {
        $file = new FileInfo('/html/case/test/|this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath5()
    {
        $file = new FileInfo('/html/case/test\>this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath6()
    {
        $file = new FileInfo('/html/case/test\,this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath7()
    {
        $file = new FileInfo('/html/case/test\<this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath8()
    {
        $file = new FileInfo('/html/case/test\|this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath9()
    {
        $file = new FileInfo('/html/case/test>/this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath10()
    {
        $file = new FileInfo('/html/case/test,/this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath11()
    {
        $file = new FileInfo('/html/case/test</this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath12()
    {
        $file = new FileInfo('/html/case/test|/this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath13()
    {
        $file = new FileInfo('/html/case/test>\this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath14()
    {
        $file = new FileInfo('/html/case/test,\this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath15()
    {
        $file = new FileInfo('/html/case/test<\this/homepage.xml');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testFileInfoConstructorWithInvalidPath16()
    {
        $file = new FileInfo('/html/case/test|\this/homepage.xml');
    }
    
    public function testCreate()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertFileExists('./tests/test.php');
    }
    
    /*
    public function testCreateWrongDirectory()
    {
        $file = new FileInfo('./public/test.php');
        $file->create();
        $this->assertFileExists('./public/test.php');
    }
   */
    
    public function testDelete()
    {
        $file = new FileInfo('./tests/test.php');
        $file->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\FileNotFoundException
     */
    public function testDelete2ndTime()
    {
        $file = new FileInfo('./tests/test.php');
        $file->delete();
    }
    
    public function testRename()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->rename('testNR2.php');
        $this->assertFileExists('./tests/testNR2.php');
        $file->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\FileNotFoundException
     */
    public function testRenameWithNonExistingFile()
    {
        $file = new FileInfo('./tests/test.php');
        $file->rename('testNR2.php');
    }
    
    public function testMove()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->move('./tests/testNR2.php');
        $this->assertFileExists('./tests/testNR2.php');
        $file->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\FileNotFoundException
     */
    public function testMoveWithNonExistingFile()
    {
        $file = new FileInfo('./tests/test.php');
        $file->move('./tests/testNR2.php');
    }
    
    public function testGetFilename()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertEquals('test.php', $file->getFilename());
        $file->delete();
    }
    
    public function testGetFullName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertEquals('./tests/test.php', $file->getFullName());
        $file->delete();
    }
    
    public function testGetName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertEquals('test', $file->getName());
        $file->delete();
    }
    
    public function testSetName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setName('SetTest');
        $this->assertEquals('SetTest', $file->getName());
        $file->delete();
    }
    
    public function testGetExtension()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertEquals('php', $file->getExtension());
        $file->delete();
    }
    
    public function testGetExtensionWithNoExtension()
    {
        $file = new FileInfo('./tests/test');
        $file->create();
        $this->assertEmpty($file->getExtension());
        $file->delete();
    }
    
    public function testSetExtension()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExtension('xml');
        $this->assertEquals('xml', $file->getExtension());
        $file->delete();
    }
    
    public function testGetLength()
    {
        $file = new FileInfo('./tests/PathTest.php');
        $this->assertGreaterThan(0, $file->getLength());
    }
    
    public function testSetLength()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setLength(500);
        $this->assertEquals(500, $file->getLength());
        $file->delete();
    }
    
    public function testGetChanged()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertInstanceOf(DateTime::class, $file->getChanged());
        $file->delete();
    }
    
    public function testGetAccessed()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertInstanceOf(DateTime::class, $file->getAccessed());
        $file->delete();
    }
    
    public function testGetContent()
    {
        $file = new FileInfo('./tests/PathTest.php');
        $this->assertGreaterThan(0, strlen($file->getContent()));
    }
    
    public function testSetContent()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setContent('Hello World!');
        $this->assertEquals('Hello World!', $file->getContent());
        $file->delete();
    }
    
    public function testWriteContent()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setContent('Hello World!');
        $file->write();
        unset($file);
        $file = new FileInfo('./tests/test.php');
        $this->assertEquals('Hello World!', $file->getContent());
        $file->delete();
    }
    
    public function testWriteContentIfFileNonExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->setContent('Hello World 2!');
        $file->write();
        $this->assertFalse($file->write());
    }
    
    public function testGetDirectory()
    {
        $file = new FileInfo('./tests/test.php');
        $this->assertInstanceOf(DirectoryInfo::class, $file->getDirectory());
    }
    
    public function testSetDirectory()
    {
        $file = new FileInfo('./tests/test.php');
        $dir = new DirectoryInfo('./tests/new');
        $file->setDirectory($dir);
        $newDir = $file->getDirectory();
        $this->assertEquals('new', $newDir->getName());
    }
    
    public function testGetExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertTrue($file->getExists());
        $file->delete();
    }
    
    public function testGetExistsWithNonExistingFile()
    {
        $file = new FileInfo('./tests/test.php');
        $this->assertFalse($file->getExists());
    }
    
    public function testSetExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->setExists(true);
        $this->assertTrue($file->getExists());
    }
    
    /**
     * @expectedException Izzle\IO\Exception\FileNotFoundException
     */
    public function testUnsetExistsAndDelete()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExists(false);
        $this->assertFalse($file->getExists());
        $file->delete();
    }
    
    public function testUnsetExistsAndSetAndDelete()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExists(false);
        $this->assertFalse($file->getExists());
        $file->setExists(true);
        $this->assertTrue($file->getExists());
        $file->delete();
    }
    
    public function testGetIsReadOnly()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertFalse($file->getIsReadOnly());
        $file->delete();
    }
    
    public function testSetIsReadOnly()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $this->assertFalse($file->getIsReadOnly());
        $file->setIsReadOnly(true);
        $this->assertTrue($file->getIsReadOnly());
        $file->setIsReadOnly(false);
        $this->assertFalse($file->getIsReadOnly());
        $file->delete();
    }
}
