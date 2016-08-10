<?php

use Izzle\IO\Directory;
use Izzle\IO\DirectoryInfo;
use Izzle\IO\FileInfo;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    
    public function testGetCurrentDirectory_Create_NonRecursiveDelete()
    {
        Directory::create('./tests/Active');
        
        $this->assertInstanceOf(DirectoryInfo::class, Directory::getCurrentDirectory());
        
        Directory::delete('./tests/Active');
    }
    
    public function testRecursiveDelete()
    {
        Directory::create('./tests/Active');
        Directory::create('./tests/Active/dir1');
        Directory::create('./tests/Active/dir2');
        Directory::delete('./tests/Active', true);
    }
    
    public function testClean()
    {
        Directory::create('./tests/Active');
        Directory::create('./tests/Active/dir1');
        Directory::create('./tests/Active/dir2');
        $tmp = Directory::clean('./tests/Active');
        $this->assertTrue($tmp);
        Directory::delete('./tests/Active');
    }
    
    public function testExists()
    {
        Directory::create('./tests/Active');
        $this->assertTrue(Directory::exists('./tests/Active'));
        $this->assertFalse(Directory::exists('./tests/Active2'));
        Directory::delete('./tests/Active');
    }
    
    public function testMove()
    {
        Directory::create('./tests/Active1');
        Directory::create('./tests/Active2');
        Directory::create('./tests/Active1/DIR');
        $this->assertTrue(Directory::move('./tests/Active1/DIR', './tests/Active2/DIR'));
        Directory::delete('./tests/Active1');
        Directory::delete('./tests/Active2', true);
    }
    
    public function testRename()
    {
        Directory::create('./tests/Active1');
        $this->assertTrue(Directory::rename('./tests/Active1', 'Active2'));
        Directory::delete('./tests/Active2');
    }
    
    public function testGetFiles()
    {
        Directory::create('./tests/Active');
        Directory::create('./tests/Active/Subfolder');
        $file1 = new FileInfo('./tests/Active/index.html');
        $file1->create();
        $file2 = new FileInfo('./tests/Active/readme.md');
        $file2->create();
        $file3 = new FileInfo('./tests/Active/unit.html');
        $file3->create();
        $file4 = new FileInfo('./tests/Active/Subfolder/message.html');
        $file4->create();
        
        $files = Directory::getFiles('./tests/Active');
        foreach ($files as $file) {
            $this->assertInstanceOf(FileInfo::class, $file);
        }
        //with search
        $files = Directory::getFiles('./tests/Active', '*.html');
        $this->assertEquals(2, count($files));
        foreach ($files as $file) {
            $this->assertInstanceOf(FileInfo::class, $file);
        }
        
        //with search + recursive
        $files = Directory::getFiles('./tests/Active', '*.html', true);
        $this->assertEquals(3, count($files));
        foreach ($files as $file) {
            $this->assertInstanceOf(FileInfo::class, $file);
        }
        Directory::delete('./tests/Active', true);
    }
    
    public function testGetDirectories()
    {
        Directory::create('./tests/Active');
        Directory::create('./tests/Active/Subfolder1');
        Directory::create('./tests/Active/Subfolder2');
        Directory::create('./tests/Active/Subfolder3');
        Directory::create('./tests/Active/ERROR');
        
        $dirs = Directory::getDirectories('./tests/Active');
        $this->assertEquals(4, count($dirs));
        
        foreach ($dirs as $dir) {
            $this->assertInstanceOf(DirectoryInfo::class, $dir);
        }
        //with search
        $dirs = Directory::getDirectories('./tests/Active', 'Subfold*');
        $this->assertEquals(3, count($dirs));
        
        foreach ($dirs as $dir) {
            $this->assertInstanceOf(DirectoryInfo::class, $dir);
        }
        Directory::delete('./tests/Active', true);
    }
}
