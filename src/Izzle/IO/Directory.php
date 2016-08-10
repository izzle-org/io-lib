<?php
namespace Izzle\IO;

class Directory
{
    protected static $currentDirectory;

    public static function getCurrentDirectory()
    {
        return self::$currentDirectory;
    }

    public static function create($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->create();
    }

    public static function delete($path, $recursive = false)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->delete($recursive);
    }

    public static function clean($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->clean();
    }

    public static function exists($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getExists();
    }

    public static function move($sourcePath, $destPath)
    {
        self::$currentDirectory = new DirectoryInfo($sourcePath);

        return self::$currentDirectory->move($destPath);
    }

    public static function rename($path, $name)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->rename($name);
    }

    public static function getFiles($path, $search = null, $recursive = false)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getFiles($search, $recursive);
    }

    public static function getDirectories($path, $search = null)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getDirectories($search);
    }

    public static function getParent($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return new DirectoryInfo(self::$currentDirectory->getParent());
    }
}
