<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\Exception\DirectoryNotEmptyException;
use Izzle\IO\Exception\DirectoryNotFoundException;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class DirectoryInfoTest extends TestCase
{
    public function testParentIsInitialized(): void
    {
        self::assertNull((new DirectoryInfo('/html/case/test/this'))->getParent());
    }

    public function testDirectoryInfoConstructorWithFullpath(): void
    {
        $dir = new DirectoryInfo('/html/case/test/this');
        self::assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    public function testDirectoryInfoConstructorWithFullpathAndSecondParam(): void
    {
        $dir = new DirectoryInfo('/html/case/test/this', false);
        self::assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath1(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/>this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath2(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/,this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath3(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/<this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath4(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/|this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath5(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\>this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath6(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\,this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath7(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\<this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath8(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\|this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath9(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test>/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath10(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test,/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath11(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test</this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath12(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test|/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath13(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test>\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath14(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test,\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath15(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test<\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath16(): void
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test|\this');
    }
    
    public function testCreate(): void
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        self::assertTrue($dir->getExists());
    }
    
    public function testCreate2ndTime(): void
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $tmp = $dir->create();
        self::assertTrue($dir->getExists());
        self::assertTrue($tmp);
    }
    
    public function testDelete(): void
    {
        $dir = new DirectoryInfo('./tests/testdir');
        self::assertTrue($dir->getExists());
        try {
            $dir->delete();
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
        self::assertFalse($dir->getExists());
    }

    /**
     * @throws DirectoryNotEmptyException
     */
    public function testDelete2ndTime(): void
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        self::assertFalse($dir->delete());
    }

    /**
     * @throws DirectoryNotFoundException
     * @throws Exception
     */
    public function testDeleteWithFileInDirectory(): void
    {
        $this->expectExceptionMessage("Directory not empty");
        $this->expectException(Izzle\IO\Exception\DirectoryNotEmptyException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        $file = new FileInfo('./tests/testdir/test.php');
        $file->create();
        $dir->delete();
    }

    /**
     * @throws Exception
     */
    public function testDeleteRecursive(): void
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $file = new FileInfo('./tests/testdir/test.php');
        $file->create();
        try {
            self::assertTrue($dir->delete(true));
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }

    /**
     * @throws Exception
     */
    public function testClean(): void
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        $file = new FileInfo('./tests/testdir/test.php');
        $file->create();
        try {
            $tmp = $dir->clean();
        } catch (DirectoryNotFoundException $e) {
        }
        self::assertTrue($tmp);
        try {
            $dir->delete();
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }
    
    public function testCleanWithNonExistingDir(): void
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->clean();
    }
    
    public function testMove(): void
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        try {
            $tmp = $dir->move('./tests/test2/');
        } catch (DirectoryNotFoundException $e) {
        }
        self::assertTrue($tmp);
        try {
            $dir->delete();
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }
    
    public function testMoveWithNonExistingDir(): void
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/test/');
        $dir->move('./tests/test2/');
    }
    
    public function testRename(): void
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        try {
            $tmp = $dir->rename('test2');
        } catch (DirectoryNotFoundException $e) {
        }
        self::assertTrue($tmp);
        try {
            $dir->delete();
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }
    
    public function testRenameWithNonExistingDir(): void
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/test/');
        $dir->rename('test2');
    }

    /**
     * @throws \Izzle\IO\Exception\ArgumentNullException
     */
    public function testGetFilesEmptyResult(): void
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        self::assertEmpty($dir->getFiles());
        try {
            $dir->delete();
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }

    /**
     * @throws \Izzle\IO\Exception\ArgumentNullException
     */
    public function testGetFilesRecursiveEmptyResult(): void
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        $dir2 = new DirectoryInfo('./tests/test/test2');
        $dir2->create();
        self::assertEmpty($dir->getFiles(null, true));
        try {
            $dir->delete(true);
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }

    /**
     * @throws \Izzle\IO\Exception\ArgumentNullException
     */
    public function testGetFilesSearchFiles(): void
    {
        $dir = new DirectoryInfo('./tests/ActiveTest/');
        $dir->create();
        $dir2 = new DirectoryInfo('./tests/ActiveTest/dir1');
        $dir2->setParent($dir);
        $dir2->create();
        
        $file = new FileInfo('./tests/ActiveTest/testfile1.php');
        $file->create();
        $file = new FileInfo('./tests/ActiveTest/testfile2.php');
        $file->create();
        $file = new FileInfo('./tests/ActiveTest/dir1/testfile3.php');
        $file->create();
        $files = $dir->getFiles('*.php', true);
        
        foreach ($files as $file) {
            self::assertInstanceOf(FileInfo::class, $file);
        }
    
        try {
            $dir->delete(true);
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }
    
    public function testGetDirectories(): void
    {
        $dir1= new DirectoryInfo('./tests/Active/');
        $dir1->create();
        $dir2 = new DirectoryInfo('./tests/Active/dir1/');
        $dir2->create();
        $dir3 = new DirectoryInfo('./tests/Active/dir3/');
        $dir3->create();
        $dir4 = new DirectoryInfo('./tests/Active/dir1/dir2');
        $dir4->create();
        
        $dirs = $dir1->getDirectories('dirs*');
        
        foreach ($dirs as $dir) {
            self::assertInstanceOf(DirectoryInfo::class, $dir);
        }
    
        try {
            self::assertTrue($dir1->delete(true));
        } catch (DirectoryNotEmptyException $e) {
        } catch (DirectoryNotFoundException $e) {
        }
    }
    
    
}
