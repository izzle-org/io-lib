<?php

use Izzle\IO\DirectoryInfo;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class DirectoryInfoTest extends TestCase
{
    public function testDirectoryInfoConstructorWithFullpath()
    {
        $dir = new DirectoryInfo('/html/case/test/this');
        $this->assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    public function testDirectoryInfoConstructorWithFullpathAndSecondParam()
    {
        $dir = new DirectoryInfo('/html/case/test/this', false);
        $this->assertInstanceOf(DirectoryInfo::class, $dir);
    }
    
    /**
     * @expectedException Izzle\IO\Exception\ArgumentNullException
     * @expectedExceptionMessage path is null
     */
    public function testDirectoryInfoConstructorWithNoPath()
    {
        new DirectoryInfo(null);
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath1()
    {
        new DirectoryInfo('/html/case/test/>this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath2()
    {
        new DirectoryInfo('/html/case/test/,this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath3()
    {
        new DirectoryInfo('/html/case/test/<this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath4()
    {
        new DirectoryInfo('/html/case/test/|this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath5()
    {
        new DirectoryInfo('/html/case/test\>this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath6()
    {
        new DirectoryInfo('/html/case/test\,this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath7()
    {
        new DirectoryInfo('/html/case/test\<this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath8()
    {
        new DirectoryInfo('/html/case/test\|this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath9()
    {
        new DirectoryInfo('/html/case/test>/this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath10()
    {
        new DirectoryInfo('/html/case/test,/this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath11()
    {
        new DirectoryInfo('/html/case/test</this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath12()
    {
        new DirectoryInfo('/html/case/test|/this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath13()
    {
        new DirectoryInfo('/html/case/test>\this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath14()
    {
        new DirectoryInfo('/html/case/test,\this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath15()
    {
        new DirectoryInfo('/html/case/test<\this');
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid path characters
     */
    public function testDirectoryInfoConstructorWithInvalidPath16()
    {
        new DirectoryInfo('/html/case/test|\this');
    }
    
    public function testCreate()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        $this->assertTrue($dir->getExists());
    }
    
    public function testCreate2ndTime()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $tmp = $dir->create();
        $this->assertTrue($dir->getExists());
        $this->assertTrue($tmp);
    }
    
    public function testDelete()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $this->assertTrue($dir->getExists());
        $dir->delete();
        $this->assertFalse($dir->getExists());
    }
    
    /**
     * @expectedException Izzle\IO\Exception\DirectoryNotFoundException
     */
    public function testDelete2ndTime()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $this->assertFalse($dir->delete());
    }
    
    /**
     * @expectedException Izzle\IO\Exception\DirectoryNotEmptyException
     * @expectedExceptionMessage Directory not empty
     */
    public function testDeleteWithFileInDirectory()
    {
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
        $dir->delete(true);
    }
    
    public function testClean()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->create();
        $file = new \Izzle\IO\FileInfo('./tests/testdir/test.php');
        $file->create();
        $tmp = $dir->clean();
        $this->assertTrue($tmp);
        $dir->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\DirectoryNotFoundException
     */
    public function testCleanWithNonExistingDir()
    {
        $dir = new DirectoryInfo('./tests/testdir');
        $dir->clean();
    }
    
    public function testMove()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        $tmp = $dir->move('./tests/test2/');
        $this->assertTrue($tmp);
        $dir->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\DirectoryNotFoundException
     */
    public function testMoveWithNonExistingDir()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->move('./tests/test2/');
    }
    
    public function testRename()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        $tmp = $dir->rename('test2');
        $this->assertTrue($tmp);
        $dir->delete();
    }
    
    /**
     * @expectedException Izzle\IO\Exception\DirectoryNotFoundException
     */
    public function testRenameWithNonExistingDir()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->rename('test2');
    }
    
    public function testGetFilesEmptyResult()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        $this->assertEmpty($dir->getFiles());
        $dir->delete();
    }
    
    public function testGetFilesRecursiveEmptyResult()
    {
        $dir = new DirectoryInfo('./tests/test/');
        $dir->create();
        $dir2 = new DirectoryInfo('./tests/test/test2');
        $dir2->create();
        $this->assertEmpty($dir->getFiles(null, true));
        $dir->delete(true);
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
            $this->assertInstanceOf(FileInfo::class, $file);
        }
        
        $dir->delete(true);
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
            $this->assertInstanceOf(DirectoryInfo::class, $dir);
        }
        
        $dir1->delete(true);
    }
    
    
}
