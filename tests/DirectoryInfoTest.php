<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\Exception\DirectoryNotEmptyException;
use Izzle\IO\Exception\DirectoryNotFoundException;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class DirectoryInfoTest extends TestCase
{
    public function testDirectoryInfoConstructorWithFullpath()
    {
        $dir = new DirectoryInfo('/html/case/test/this');
        self::assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    public function testDirectoryInfoConstructorWithFullpathAndSecondParam()
    {
        $dir = new DirectoryInfo('/html/case/test/this', false);
        self::assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    public function testDirectoryInfoConstructorWithNoPath()
    {
        $this->expectExceptionMessage("path is null");
        $this->expectException(Izzle\IO\Exception\ArgumentNullException::class);
        new DirectoryInfo(null);
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath1()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/>this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath2()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/,this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath3()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/<this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath4()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test/|this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath5()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\>this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath6()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\,this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath7()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\<this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath8()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test\|this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath9()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test>/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath10()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test,/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath11()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test</this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath12()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test|/this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath13()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test>\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath14()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test,\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath15()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test<\this');
    }
    
    public function testDirectoryInfoConstructorWithInvalidPath16()
    {
        $this->expectExceptionMessage("invalid path characters");
        $this->expectException(InvalidArgumentException::class);
        new DirectoryInfo('/html/case/test|\this');
    }
    
    public function testCreate()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        self::assertTrue($dir->getExists());
    }
    
    public function testCreate2ndTime()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $tmp = $dir->create();
        self::assertTrue($dir->getExists());
        self::assertTrue($tmp);
    }
    
    public function testDelete()
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
    
    public function testDelete2ndTime()
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        self::assertFalse($dir->delete());
    }
    
    public function testDeleteWithFileInDirectory()
    {
        $this->expectExceptionMessage("Directory not empty");
        $this->expectException(Izzle\IO\Exception\DirectoryNotEmptyException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        $file = new FileInfo('./tests/testdir/test.php');
        $file->create();
        $dir->delete();
    }
    
    public function testDeleteRecursive()
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
    
    public function testClean()
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
    
    public function testCleanWithNonExistingDir()
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->clean();
    }
    
    public function testMove()
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
    
    public function testMoveWithNonExistingDir()
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/test/');
        $dir->move('./tests/test2/');
    }
    
    public function testRename()
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
    
    public function testRenameWithNonExistingDir()
    {
        $this->expectException(Izzle\IO\Exception\DirectoryNotFoundException::class);
        $dir = new DirectoryInfo('./tests/test/');
        $dir->rename('test2');
    }
    
    public function testGetFilesEmptyResult()
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
    
    public function testGetFilesRecursiveEmptyResult()
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
    
    public function testGetFilesSearchFiles()
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
    
    public function testGetDirectories()
    {
        $dir1= new DirectoryInfo('./tests/Active/');
        $dir1->create();
        $dir2 = new DirectoryInfo('./tests/Active/dir1/');
        $dir2->create();
        $dir3 = new DirectoryInfo('./tests/Active/dir3/');
        $dir3->create();
        $dir4 = new DirectoryInfo('./tests/Active/dir1/dir2');
        $dir4->create();
        
        $dirs = $dir1->getDirectories();
        
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
