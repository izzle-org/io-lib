<?php
namespace Izzle\IO;

class Directory
{
    /**
     * @var DirectoryInfo
     */
    protected static $currentDirectory;
    
    /**
     * @return DirectoryInfo
     */
    public static function getCurrentDirectory()
    {
        return self::$currentDirectory;
    }
    
    /**
     * @param string $path
     * @return bool
     */
    public static function create($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->create();
    }
    
    /**
     * @param string $path
     * @param FileInfo $file
     * @return bool
     */
    public static function createHashed($path, FileInfo $file)
    {
        $md5hash = md5_file($file->getFullName());
        $real_path = array_slice(str_split($md5hash, 2), 0, 3);
        $dir = Path::combine($path, join(DIRECTORY_SEPARATOR, $real_path));
    
        self::$currentDirectory = new DirectoryInfo($dir);
    
        return self::$currentDirectory->create();
    }
    
    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     */
    public static function delete($path, $recursive = false)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->delete($recursive);
    }
    
    /**
     * @param string $path
     * @return bool
     */
    public static function clean($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->clean();
    }
    
    /**
     * @param string $path
     * @return bool
     */
    public static function exists($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getExists();
    }
    
    /**
     * @param string $sourcePath
     * @param string $destPath
     * @return bool
     */
    public static function move($sourcePath, $destPath)
    {
        self::$currentDirectory = new DirectoryInfo($sourcePath);

        return self::$currentDirectory->move($destPath);
    }
    
    /**
     * @param string $path
     * @param string $name
     * @return bool
     */
    public static function rename($path, $name)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->rename($name);
    }
    
    /**
     * @param string $path
     * @param string|null $search
     * @param bool $recursive
     * @return array
     */
    public static function getFiles($path, $search = null, $recursive = false)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getFiles($search, $recursive);
    }
    
    /**
     * @param string $path
     * @param string|null $search
     * @return array
     */
    public static function getDirectories($path, $search = null)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getDirectories($search);
    }
    
    /**
     * @param string $path
     * @return DirectoryInfo
     */
    public static function getParent($path)
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return new DirectoryInfo(self::$currentDirectory->getParent());
    }
}
