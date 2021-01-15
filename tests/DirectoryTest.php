<?php

use Izzle\IO\Directory;
use Izzle\IO\DirectoryInfo;
use Izzle\IO\FileInfo;
Use Izzle\IO\Path;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    public function testGetCurrentDirectory_Create_NonRecursiveDelete()
    {
        Directory::create('./tests/Active');
        
        self::assertInstanceOf(DirectoryInfo::class, Directory::getCurrentDirectory());
        
        Directory::delete('./tests/Active');
    }
    
    public function testGetCurrenDirectory_Create_Hashed_RecursiveDelete()
    {
        $filename = Path::combine(sys_get_temp_dir(), 'test.jpg');
        touch($filename);
        try {
            $file = new FileInfo($filename);
        } catch (\Izzle\IO\Exception\ArgumentNullException $e) {
        }
    
        Directory::createHashed('./tests/Active', $file);
    
        self::assertInstanceOf(DirectoryInfo::class, Directory::getCurrentDirectory());
    
        Directory::delete('./tests/Active', true);
        
        unlink($filename);
    }
    
    public function testRecursiveDelete()
    {
        self::assertTrue(Directory::create('./tests/Active'));
        self::assertTrue(Directory::create('./tests/Active/dir1'));
        self::assertTrue(Directory::create('./tests/Active/dir2'));
        self::assertTrue(Directory::delete('./tests/Active', true));
    }
    
    public function testClean()
    {
        Directory::create('./tests/Active');
        Directory::create('./tests/Active/dir1');
        Directory::create('./tests/Active/dir2');
        $tmp = Directory::clean('./tests/Active');
        self::assertTrue($tmp);
        Directory::delete('./tests/Active');
    }
    
    public function testExists()
    {
        Directory::create('./tests/Active');
        self::assertTrue(Directory::exists('./tests/Active'));
        self::assertFalse(Directory::exists('./tests/Active2'));
        Directory::delete('./tests/Active');
    }
    
    public function testMove()
    {
        Directory::create('./tests/Active1');
        Directory::create('./tests/Active2');
        Directory::create('./tests/Active1/DIR');
        self::assertTrue(Directory::move('./tests/Active1/DIR', './tests/Active2/DIR'));
        Directory::delete('./tests/Active1');
        Directory::delete('./tests/Active2', true);
    }
    
    public function testRename()
    {
        Directory::create('./tests/Active1');
        self::assertTrue(Directory::rename('./tests/Active1', 'Active2'));
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
            self::assertInstanceOf(FileInfo::class, $file);
        }
        
        //with search
        $files = Directory::getFiles('./tests/Active', '*.html');
        self::assertCount(2, $files);
        
        foreach ($files as $file) {
            self::assertInstanceOf(FileInfo::class, $file);
        }
        
        //with search + recursive
        $files = Directory::getFiles('./tests/Active', '*.html', true);
        self::assertCount(3, $files);
        
        foreach ($files as $file) {
            self::assertInstanceOf(FileInfo::class, $file);
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
        self::assertCount(4, $dirs);
        
        foreach ($dirs as $dir) {
            self::assertInstanceOf(DirectoryInfo::class, $dir);
        }
        
        //with search
        $dirs = Directory::getDirectories('./tests/Active', 'Subfold*');
        self::assertCount(3, $dirs);
        
        foreach ($dirs as $dir) {
            self::assertInstanceOf(DirectoryInfo::class, $dir);
        }
        
        Directory::delete('./tests/Active', true);
    }
}
