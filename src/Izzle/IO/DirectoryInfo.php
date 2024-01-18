<?php
namespace Izzle\IO;

use Exception;
use FilesystemIterator;
use InvalidArgumentException;
use Izzle\IO\Exception\ArgumentNullException;
use Izzle\IO\Exception\DirectoryNotEmptyException;
use Izzle\IO\Exception\DirectoryNotFoundException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DirectoryInfo
{
    protected string $fullName;
    protected string $name;
    protected ?DirectoryInfo $parent = null;
    protected bool $exists;
    protected bool $empty;
    protected bool $loadParent;
    
    /**
     * @param string $path
     * @param bool|true $parent
     * @throws InvalidArgumentException
     */
    public function __construct(string $path, bool $parent = true)
    {
        $this->loadParent = $parent;
        
        if (preg_match('/[\,\<\>\|]/', $path)) {
            throw new InvalidArgumentException('invalid path characters');
        }
        
        $this->setFullName($path)
            ->setName(basename($this->fullName))
            ->setExists(file_exists($this->fullName))
            ->setEmpty($this->isEmpty());
        
        $this->getInfos();
    }
    
    /**
     * @return bool
     */
    public function create(): bool
    {
        if (!$this->exists) {
            if ($this->exists = mkdir($this->fullName, 0777, true)) {
                $this->getInfos();
            }
            
            return $this->exists;
        }
        
        return true;
    }
    
    /**
     * @param bool|false $recursive
     * @return bool
     * @throws DirectoryNotEmptyException
     * @throws DirectoryNotFoundException
     */
    public function delete(bool $recursive = false): bool
    {
        if (!$this->exists) {
            throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
        }
    
        $this->setEmpty($this->isEmpty());
        
        if (!$recursive && !$this->getEmpty()) {
            throw new DirectoryNotEmptyException("Directory not empty");
        }
        
        $this->setExists(false);

        if ($this->empty) {
            return rmdir($this->fullName);
        }

        if ($recursive) {
            return $this->deleteRecursively();
        }

        return false;
    }
    
    /**
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function clean(): bool
    {
        if ($this->exists) {
            $result = $this->cleanRecursively();
            $this->setEmpty($result);
            
            return $result;
        }

        throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
    }
    
    /**
     * @param string $destPath
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function move(string $destPath): bool
    {
        if (!$this->exists) {
            throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
        }
        
        if (rename($this->fullName, $destPath)) {
            $this->name = basename($destPath);
            $this->fullName = $destPath;
            
            return true;
        }
        
        return false;
    }

    /**
     * @param string $name
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function rename(string $name): bool
    {
        if (!$this->exists) {
            throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
        }
        
        $destPath = Path::combine($this->getParent()->getFullName(), $name);
        if (rename($this->fullName, $destPath)) {
            $this->name = $name;
            $this->fullName = $destPath;
            
            return true;
        }
        
        return false;
    }

    /**
     * @param string|null $search
     * @param bool|false $recursive
     * @return array
     * @throws ArgumentNullException
     * @throws Exception
     * @throws Exception
     */
    public function getFiles(string $search = null, bool $recursive = false): array
    {
        $fileInfos = [];
        $search = $search ?? '*';
        if ($recursive) {
            $this->getFilesRecursively($this->fullName, $search, $fileInfos);
        } else {
            foreach (glob(Path::combine($this->fullName, $search)) as $file) {
                if (!is_dir($file)) {
                    $fileInfos[] = new FileInfo($file);
                }
            }
        }
        
        return $fileInfos;
    }

    /**
     * @param string $fullName
     * @param string $search
     * @param array $fileInfos
     * @throws ArgumentNullException
     * @throws Exception
     * @throws Exception
     */
    protected function getFilesRecursively(string $fullName, string $search, array &$fileInfos): void
    {
        foreach (glob(Path::combine($fullName, '*'), GLOB_ONLYDIR) as $dir) {
            $this->getFilesRecursively($dir, $search, $fileInfos);
        }
        
        foreach (glob(Path::combine($fullName, $search)) as $file) {
            if (!is_dir($file)) {
                $fileInfos[] = new FileInfo($file);
            }
        }
    }

    /**
     * @param string|null $search
     * @return array
     */
    public function getDirectories(string $search = null): array
    {
        $directoryInfos = [];
        $search = $search ?? '*';
        foreach (glob(Path::combine($this->fullName, $search), GLOB_ONLYDIR) as $dir) {
            $directoryInfos[] = new self($dir);
        }
        
        return $directoryInfos;
    }
    
    /**
     * @return bool
     */
    protected function deleteRecursively(): bool
    {
        if (class_exists('RecursiveDirectoryIterator') && class_exists('RecursiveIteratorIterator')) {
            return $this->deleteRecursivelySpl();
        }

        return $this->deleteRecursivelyWithoutSpl($this->fullName);
    }
    
    /**
     * @param bool|true $delDir
     * @return bool
     */
    protected function deleteRecursivelySpl(bool $delDir = true): bool
    {
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->fullName,
            FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
        }
        
        return $delDir ? rmdir($this->fullName) : true;
    }
    
    /**
     * @param string $path
     * @param bool|true $delDir
     * @return bool
     */
    protected function deleteRecursivelyWithoutSpl(string $path, bool $delDir = true): bool
    {
        $files = array_diff(scandir($path), ['.', '..']);
        foreach ($files as $file) {
            $filePath = Path::combine($path, $file);
            
            (is_dir("{$filePath}")) ? $this->deleteRecursivelyWithoutSpl("{$filePath}") : unlink("{$filePath}");
        }
        
        return $delDir ? rmdir($path) : true;
    }
    
    /**
     * @return bool
     */
    protected function cleanRecursively(): bool
    {
        if (class_exists('RecursiveDirectoryIterator') && class_exists('RecursiveIteratorIterator')) {
            return $this->deleteRecursivelySpl(false);
        }

        return $this->deleteRecursivelyWithoutSpl($this->fullName, false);
    }
    
    /**
     * @return bool
     */
    protected function isEmpty(): bool
    {
        if ($this->exists) {
            return (count(scandir($this->fullName)) === 2);
        }
        
        return true;
    }
    
    protected function getInfos(): void
    {
        if ($this->exists) {
            $this->setFullName($this->fullName);
        }
        
        $parentPath = dirname($this->fullName);
        if ($this->loadParent && $parentPath !== $this->fullName && is_dir($parentPath)) {
            $this->setParent(new DirectoryInfo($parentPath, false));
        }
    }
    
    /**
     * Gets the value of fullName.
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }
    
    /**
     * Sets the value of fullName.
     * @param string $fullName the full name
     * @return self
     */
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        
        return $this;
    }
    
    /**
     * Gets the value of name.
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Sets the value of name.
     * @param string $name the name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Gets the value of parent.
     * @return DirectoryInfo|null
     */
    public function getParent(): ?DirectoryInfo
    {
        $parentPath = dirname($this->fullName);
        if ($this->parent === null && is_dir($parentPath) && $parentPath != $this->fullName) {
            $this->setParent(new DirectoryInfo($parentPath, false));
        }
        
        return $this->parent;
    }
    
    /**
     * Sets the value of parent.
     * @param DirectoryInfo $parent the parent
     * @return self
     */
    public function setParent(DirectoryInfo $parent): self
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    /**
     * Gets the value of exists.
     * @return bool
     */
    public function getExists(): bool
    {
        return $this->exists;
    }
    
    /**
     * Sets the value of exists.
     * @param bool $exists the exists
     * @return self
     */
    public function setExists(bool $exists): self
    {
        $this->exists = $exists;
        
        return $this;
    }
    
    /**
     * Gets the value of empty.
     * @return bool
     */
    public function getEmpty(): bool
    {
        return $this->empty;
    }
    
    /**
     * Sets the value of empty.
     * @param bool $empty the empty
     * @return self
     */
    public function setEmpty(bool $empty): self
    {
        $this->empty = $empty;
        
        return $this;
    }
}
