<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\Exception\FileNotFoundException;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class FileInfoTest extends TestCase
{
    public function testAllPropertiesAreInitiated(): void
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml', false);
        self::assertNull($file->getAccessed());
        self::assertNull($file->getChanged());
        self::assertNull($file->getDirectory());
        self::assertNull($file->getContent());
    }

    public function testFileInfoConstructorWithFullpath(): void
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml');
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithFullpathAndSecondParam(): void
    {
        $file = new FileInfo('/html/case/test/this/homepage.xml', false);
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectoryAndSecondParam(): void
    {
        $file = new FileInfo('homepage.xml', false);
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithNoDirectory(): void
    {
        $file = new FileInfo('homepage.xml');
        self::assertInstanceOf(FileInfo::class, $file);
    }
    
    public function testFileInfoConstructorWithInvalidPath1(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test/>this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath2(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test/,this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath3(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test/<this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath4(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test/|this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath5(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test\>this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath6(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test\,this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath7(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test\<this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath8(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test\|this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath9(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test>/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath10(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test,/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath11(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test</this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath12(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test|/this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath13(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test>\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath14(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test,\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath15(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test<\this/homepage.xml');
    }
    
    public function testFileInfoConstructorWithInvalidPath16(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new FileInfo('/html/case/test|\this/homepage.xml');
    }

    /**
     * @throws Exception
     */
    public function testCreate(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertFileExists('./tests/test.php');
    }
    
    public function testDelete(): void
    {
        $file = new FileInfo('./tests/test.php');
        try {
            self::assertTrue($file->delete());
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testDelete2ndTime(): void
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->delete();
    }

    /**
     * @throws Exception
     */
    public function testRename(): void
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
    
    public function testRenameWithNonExistingFile(): void
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->rename('testNR2.php');
    }

    /**
     * @throws Exception
     */
    public function testMove(): void
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
    
    public function testMoveWithNonExistingFile(): void
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->move('./tests/testNR2.php');
    }

    /**
     * @throws Exception
     */
    public function testGetFilename(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('test.php', $file->getFilename());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testGetFullName(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('./tests/test.php', $file->getFullName());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testGetName(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('test', $file->getName());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testSetName(): void
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

    /**
     * @throws Exception
     */
    public function testGetExtension(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertEquals('php', $file->getExtension());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testGetExtensionWithNoExtension(): void
    {
        $file = new FileInfo('./tests/test');
        $file->create();
        self::assertEmpty($file->getExtension());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testSetExtension(): void
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
    
    public function testGetLength(): void
    {
        $file = new FileInfo('./tests/PathTest.php');
        self::assertGreaterThan(0, $file->getLength());
    }

    /**
     * @throws Exception
     */
    public function testSetLength(): void
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

    /**
     * @throws Exception
     */
    public function testGetChanged(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertInstanceOf(DateTime::class, $file->getChanged());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testGetAccessed(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertInstanceOf(DateTime::class, $file->getAccessed());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetContent(): void
    {
        $file = new FileInfo('./tests/PathTest.php');
        self::assertGreaterThan(0, strlen($file->getContent()));
    }

    /**
     * @throws Exception
     */
    public function testSetContent(): void
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

    /**
     * @throws Exception
     */
    public function testWriteContent(): void
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
    
    public function testWriteContentIfFileNonExists(): void
    {
        $file = new FileInfo('./tests/test.php');

        try {
            $file->delete();
        } catch (FileNotFoundException $e){}

        $file->setContent('Hello World 2!');
        $file->write();
        self::assertFalse($file->write());
    }
    
    public function testGetDirectory(): void
    {
        $file = new FileInfo('./tests/test.php');
        self::assertInstanceOf(DirectoryInfo::class, $file->getDirectory());
    }
    
    public function testSetDirectory(): void
    {
        $file = new FileInfo('./tests/test.php');
        $dir = new DirectoryInfo('./tests/new');
        $file->setDirectory($dir);
        $newDir = $file->getDirectory();
        self::assertEquals('new', $newDir->getName());
    }

    /**
     * @throws Exception
     */
    public function testGetExists(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertTrue($file->getExists());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }
    
    public function testGetExistsWithNonExistingFile(): void
    {
        $file = new FileInfo('./tests/test.php');
        self::assertFalse($file->getExists());
    }
    
    public function testSetExists(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->setExists(true);
        self::assertTrue($file->getExists());
    }
    
    public function testUnsetExistsAndDelete(): void
    {
        $this->expectException(Izzle\IO\Exception\FileNotFoundException::class);
        $file = new FileInfo('./tests/test.php');
        $file->create();
        $file->setExists(false);
        self::assertFalse($file->getExists());
        $file->delete();
    }

    /**
     * @throws Exception
     */
    public function testUnsetExistsAndSetAndDelete(): void
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

    /**
     * @throws Exception
     */
    public function testGetIsReadOnly(): void
    {
        $file = new FileInfo('./tests/test.php');
        $file->create();
        self::assertFalse($file->getIsReadOnly());
        try {
            $file->delete();
        } catch (FileNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testSetIsReadOnly(): void
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
