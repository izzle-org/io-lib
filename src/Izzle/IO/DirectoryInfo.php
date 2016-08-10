<?php
namespace Izzle\IO;

use Izzle\IO\Exception\ArgumentNullException;
use Izzle\IO\Exception\DirectoryNotEmptyException;
use Izzle\IO\Exception\DirectoryNotFoundException;

class DirectoryInfo
{
    protected $fullName;
    protected $name;
    protected $parent;
    protected $exists;
    protected $empty;
    protected $loadParent;
    
    /**
     * @param $path
     * @param bool|true $parent
     * @throws ArgumentNullException
     */
    public function __construct($path, $parent = true)
    {
        $this->loadParent = $parent;
        
        if ($path === null) {
            throw new ArgumentNullException('path is null');
        }
        
        if (preg_match('/[\,\<\>\|]/', $path)) {
            throw new \InvalidArgumentException('invalid path characters');
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
    public function create()
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
    public function delete($recursive = false)
    {
        if (!$this->exists) {
            throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
        }
    
        $this->setEmpty($this->isEmpty());
        
        if (!$this->getEmpty() && !$recursive) {
            throw new DirectoryNotEmptyException("Directory not empty");
        }
        
        $this->setExists(false);
        
        if ($this->empty) {
            return rmdir($this->fullName);
        } elseif ($recursive) {
            return $this->deleteRecursively();
        }
        
        return false;
    }
    
    /**
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function clean()
    {
        if ($this->exists) {
            $result = $this->cleanRecursively();
            $this->setEmpty($result);
            
            return $result;
        } else {
            throw new DirectoryNotFoundException("directory '{$this->fullName}' not found");
        }
    }
    
    /**
     * @param $destPath
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function move($destPath)
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
     * @param $name
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function rename($name)
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
     * @param null $search
     * @param bool|false $recursive
     * @return array
     */
    public function getFiles($search = null, $recursive = false)
    {
        $fileInfos = [];
        $search = ($search === null) ? '*' : $search;
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
     * @param $fullName
     * @param $search
     * @param $fileInfos
     */
    protected function getFilesRecursively($fullName, $search, &$fileInfos)
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
     * @param null $search
     * @return array
     */
    public function getDirectories($search = null)
    {
        $directoryInfos = [];
        $search = ($search === null) ? '*' : $search;
        foreach (glob(Path::combine($this->fullName, $search), GLOB_ONLYDIR) as $dir) {
            $directoryInfos[] = new self($dir);
        }
        
        return $directoryInfos;
    }
    
    /**
     * @return bool
     */
    protected function deleteRecursively()
    {
        if (class_exists('RecursiveDirectoryIterator') && class_exists('RecursiveIteratorIterator')) {
            return $this->deleteRecursivelySpl();
        } else {
            return $this->deleteRecursivelyWithoutSpl($this->fullName);
        }
    }
    
    /**
     * @param bool|true $delDir
     * @return bool
     */
    protected function deleteRecursivelySpl($delDir = true)
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->fullName,
            \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
        }
        
        return $delDir ? rmdir($this->fullName) : true;
    }
    
    /**
     * @param $path
     * @param bool|true $delDir
     * @return bool
     */
    protected function deleteRecursivelyWithoutSpl($path, $delDir = true)
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
    protected function cleanRecursively()
    {
        if (class_exists('RecursiveDirectoryIterator') && class_exists('RecursiveIteratorIterator')) {
            return $this->deleteRecursivelySpl(false);
        } else {
            return $this->deleteRecursivelyWithoutSpl($this->fullName, false);
        }
    }
    
    /**
     * @return bool
     */
    protected function isEmpty()
    {
        if ($this->exists) {
            return (count(scandir($this->fullName)) == 2);
        }
        
        return true;
    }
    
    protected function getInfos()
    {
        if ($this->exists) {
            $this->setFullName($this->fullName);
        }
        
        $parentPath = dirname($this->fullName);
        if ($this->loadParent && is_dir($parentPath) && $parentPath != $this->fullName) {
            $this->setParent(new DirectoryInfo($parentPath, false));
        }
    }
    
    /**
     * Gets the value of fullName.
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    
    /**
     * Sets the value of fullName.
     * @param string $fullName the full name
     * @return self
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        
        return $this;
    }
    
    /**
     * Gets the value of name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the value of name.
     * @param string $name the name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * Gets the value of parent.
     * @return DirectoryInfo
     */
    public function getParent()
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
    public function setParent(DirectoryInfo $parent)
    {
        $this->parent = $parent;
        
        return $this;
    }
    
    /**
     * Gets the value of exists.
     * @return bool
     */
    public function getExists()
    {
        return $this->exists;
    }
    
    /**
     * Sets the value of exists.
     * @param bool $exists the exists
     * @return self
     */
    public function setExists($exists)
    {
        $this->exists = (bool)$exists;
        
        return $this;
    }
    
    /**
     * Gets the value of empty.
     * @return bool
     */
    public function getEmpty()
    {
        return $this->empty;
    }
    
    /**
     * Sets the value of empty.
     * @param bool $empty the empty
     * @return self
     */
    public function setEmpty($empty)
    {
        $this->empty = (bool)$empty;
        
        return $this;
    }
}
