<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\Exception\FileNotFoundException;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class FileInfoTest extends TestCase
{
    public function testFileInfoConstructorWithFullpath()
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml');
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithFullpathAndSecondParam()
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml', false);
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectoryAndSecondParam()
    {
        $file = new FileInfo('homepage.xml', false);
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectory()
    {
        $file = new FileInfo('homepage.xml');
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoPath()
    {
        $this->expectExceptionMessage("path is null");
        $this->expectException(Izzle\IO\Exception\ArgumentNullException::class);
        $file = new FileInfo(null);
    }
    
    public function testFileInfoConstructorWithInvalidPath1()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test/>this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath2()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test/,this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath3()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test/<this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath4()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test/|this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath5()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test\>this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath6()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test\,this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath7()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test\<this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath8()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test\|this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath9()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test>/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath10()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test,/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath11()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test</this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath12()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test|/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath13()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test>\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath14()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test,\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath15()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test<\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath16()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        $file = new FileInfo('/html/case/test|\this/homepage.xml');
    }
    
    public function testCreate()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertFileExists('./tests/test.php');
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
        try {
            self::assertTrue($file->delete());
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testDelete2ndTime()
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->delete();
    }
    
    public function testRename()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        try {
            $file->rename('testNR2.php');
        } catch (FileNotFoundException $e) {
        }
        self::assertFileExists('./tests/testNR2.php');
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testRenameWithNonExistingFile()
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->rename('testNR2.php');
    }
    
    public function testMove()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        try {
            $file->move('./tests/testNR2.php');
        } catch (FileNotFoundException $e) {
        }
        self::assertFileExists('./tests/testNR2.php');
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testMoveWithNonExistingFile()
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->move('./tests/testNR2.php');
    }
    
    public function testGetFilename()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('test.php', $file->getFilename());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetFullName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('./tests/test.php', $file->getFullName());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('test', $file->getName());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testSetName()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setName('SetTest');
        self::assertEquals('SetTest', $file->getName());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetExtension()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('php', $file->getExtension());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetExtensionWithNoExtension()
    {
        $file = new FileInfo('./tests/test');
        $file->create();
        self::assertEmpty($file->getExtension());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testSetExtension()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExtension('xml');
        self::assertEquals('xml', $file->getExtension());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetLength()
    {
        $file = new FileInfo('./tests/PathTest.php');
        self::assertGreaterThan(0, $file->getLength());
    }
    
    public function testSetLength()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setLength(500);
        self::assertEquals(500, $file->getLength());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetChanged()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertInstanceOf(DateTime::class, $file->getChanged());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetAccessed()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertInstanceOf(DateTime::class, $file->getAccessed());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetContent()
    {
        $file = new FileInfo('./tests/PathTest.php');
        self::assertGreaterThan(0, strlen($file->getContent()));
    }
    
    public function testSetContent()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setContent('Hello World!');
        self::assertEquals('Hello World!', $file->getContent());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testWriteContent()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setContent('Hello World!');
        $file->write();
        unset($file);
        $file = new FileInfo('./tests/test.php');
        self::assertEquals('Hello World!', $file->getContent());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testWriteContentIfFileNonExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->setContent('Hello World 2!');
        $file->write();
        self::assertFalse($file->write());
    }
    
    public function testGetDirectory()
    {
        $file = new FileInfo('./tests/test.php');
        self::assertInstanceOf(DirectoryInfo::class, $file->getDirectory());
    }
    
    public function testSetDirectory()
    {
        $file = new FileInfo('./tests/test.php');
        $dir = new DirectoryInfo('./tests/new');
        $file->setDirectory($dir);
        $newDir = $file->getDirectory();
        self::assertEquals('new', $newDir->getName());
    }
    
    public function testGetExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertTrue($file->getExists());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetExistsWithNonExistingFile()
    {
        $file = new FileInfo('./tests/test.php');
        self::assertFalse($file->getExists());
    }
    
    public function testSetExists()
    {
        $file = new FileInfo('./tests/test.php');
        $file->setExists(true);
        self::assertTrue($file->getExists());
    }
    
    public function testUnsetExistsAndDelete()
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExists(false);
        self::assertFalse($file->getExists());
        $file->delete();
    }
    
    public function testUnsetExistsAndSetAndDelete()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExists(false);
        self::assertFalse($file->getExists());
        $file->setExists(true);
        self::assertTrue($file->getExists());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetIsReadOnly()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertFalse($file->getIsReadOnly());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testSetIsReadOnly()
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertFalse($file->getIsReadOnly());
        $file->setIsReadOnly(true);
        self::assertTrue($file->getIsReadOnly());
        $file->setIsReadOnly(false);
        self::assertFalse($file->getIsReadOnly());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
}
