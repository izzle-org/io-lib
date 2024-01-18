<?php
namespace Izzle\IO;

use Izzle\IO\Exception\ArgumentNullException;
use Izzle\IO\Exception\DirectoryNotEmptyException;
use Izzle\IO\Exception\DirectoryNotFoundException;

class Directory
{
    /**
     * @var DirectoryInfo|null
     */
    protected static ?DirectoryInfo $currentDirectory = null;
    
    /**
     * @return DirectoryInfo|null
     */
    public static function getCurrentDirectory(): ?DirectoryInfo
    {
        return self::$currentDirectory;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function create(string $path): bool
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->create();
    }

    /**
     * @param string $path
     * @param FileInfo $file
     * @return bool
     */
    public static function createHashed(string $path, FileInfo $file): bool
    {
        $md5hash = md5_file($file->getFullName());
        $real_path = array_slice(str_split($md5hash, 2), 0, 3);
        $dir = Path::combine($path, implode(DIRECTORY_SEPARATOR, $real_path));
    
        self::$currentDirectory = new DirectoryInfo($dir);
    
        return self::$currentDirectory->create();
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     * @throws DirectoryNotEmptyException
     * @throws DirectoryNotFoundException
     */
    public static function delete(string $path, bool $recursive = false): bool
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->delete($recursive);
    }

    /**
     * @param string $path
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public static function clean(string $path): bool
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->clean();
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function exists(string $path): bool
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getExists();
    }

    /**
     * @param string $sourcePath
     * @param string $destPath
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public static function move(string $sourcePath, string $destPath): bool
    {
        self::$currentDirectory = new DirectoryInfo($sourcePath);

        return self::$currentDirectory->move($destPath);
    }

    /**
     * @param string $path
     * @param string $name
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public static function rename(string $path, string $name): bool
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->rename($name);
    }

    /**
     * @param string $path
     * @param string|null $search
     * @param bool $recursive
     * @return array
     * @throws ArgumentNullException
     */
    public static function getFiles(string $path, string $search = null, bool $recursive = false): array
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getFiles($search, $recursive);
    }

    /**
     * @param string $path
     * @param string|null $search
     * @return array
     */
    public static function getDirectories(string $path, string $search = null): array
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return self::$currentDirectory->getDirectories($search);
    }

    /**
     * @param string $path
     * @return DirectoryInfo
     */
    public static function getParent(string $path): DirectoryInfo
    {
        self::$currentDirectory = new DirectoryInfo($path);

        return new DirectoryInfo(self::$currentDirectory->getParent());
    }
}
