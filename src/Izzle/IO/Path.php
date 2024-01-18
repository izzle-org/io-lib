<?php
namespace Izzle\IO;

use InvalidArgumentException;

class Path
{
    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public static function combine(): string
    {
        $paths = func_get_args();

        if (!is_array($paths) || count($paths) == 0) {
            throw new InvalidArgumentException('empty or invalid paths');
        }

        $path = str_replace('//', '/', implode(DIRECTORY_SEPARATOR, $paths));

        return file_exists($path) ? realpath($path) : $path;
    }
    
    /**
     * @param string $path
     * @param bool $real
     * @return string
     */
    public static function getDirectoryName(string $path, bool $real = true): string
    {
        return ($real && is_dir($path)) ? realpath(dirname($path)) : dirname($path);
    }
    
    /**
     * @param string $path
     * @return string
     */
    public static function getFileName(string $path): string
    {
        return self::hasExtension($path) ? self::getFileNameWithoutExtension($path) . '.' 
            . self::getExtension($path) : self::getFileNameWithoutExtension($path);
    }
    
    /**
     * @param string $path
     * @return string
     */
    public static function getFileNameWithoutExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }
    
    /**
     * @param string $path
     * @return string
     */
    public static function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
    
    /**
     * @param string $path
     * @return bool
     */
    public static function hasExtension(string $path): bool
    {
        return (self::getExtension($path) !== '');
    }
}
