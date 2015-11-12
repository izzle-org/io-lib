<?php
namespace Izzle\IO;

use Izzle\IO\Exception\FileNotFoundException,
    Izzle\IO\Exception\ArgumentNullException;

class FileInfo
{
    protected $fullName;
    protected $name;
    protected $extension;
    protected $length;
    protected $changed;
    protected $accessed;
    protected $content;
    protected $directory;
    protected $exists;
    protected $isReadOnly;

    /**
     * @param $path
     * @param bool|true $directory
     * @throws ArgumentNullException
     */
    public function __construct($path, $directory = true)
    {
        if ($path === null) {
            throw new ArgumentNullException('path is null');
        }

        if (preg_match('/\,\<\>\|/', $path)) {
            throw new \InvalidArgumentException('invalid path characters');
        }

        $this->setFullName($path)
            ->setName(pathinfo($this->fullName, PATHINFO_FILENAME))
            ->setExists(file_exists($this->fullName))
            ->setExtension(pathinfo($this->fullName, PATHINFO_EXTENSION))
            ->setLength(0)
            ->setIsReadOnly(false);

        if ($directory) {
            $this->setDirectory(new DirectoryInfo(pathinfo($this->fullName, PATHINFO_DIRNAME), false));
        }
        
        $this->getInfos();
    }

    /**
     * @return bool
     */
    public function create()
    {
        if (!$this->exists) {
            if ($this->exists = touch($this->fullName)) {
                $this->getInfos();
            }

            return $this->exists;
        }

        return true;
    }

    /**
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete()
    {
        if ($this->exists) {
            return unlink($this->fullName);
        } else {
            throw new FileNotFoundException("file '{$this->fullName}' not found");
        }
    }

    /**
     * @param $name
     * @return bool
     * @throws FileNotFoundException
     */
    public function rename($name)
    {
        if ($this->exists) {
            $newPath = Path::combine($this->getDirectory()->getFullName(), $name);
            if (rename($this->fullName, $newPath)) {
                $this->name = $name;
                $this->fullName = $newPath;

                return true;
            }

            return false;
        } else {
            throw new FileNotFoundException("file '{$this->fullName}' not found");
        }
    }

    /**
     * @param $name
     * @return bool
     * @throws FileNotFoundException
     */
    public function move($name)
    {
        if ($this->exists) {
            if (rename($this->fullName, $name)) {
                $this->name = basename($name);
                $this->fullName = $name;

                return true;
            }

            return false;
        } else {
            throw new FileNotFoundException("file '{$this->fullName}' not found");
        }
    }

    protected function getInfos()
    {
        if ($this->exists) {
            $this->setFullName($this->fullName)
                ->setIsReadOnly(!is_readable($this->fullName));

            $fileStats = stat($this->fullName);
            $this->setLength($fileStats[7]);
            $this->changed = new \DateTime('@' . $fileStats[9]);
            $this->accessed = new \DateTime('@' . $fileStats[8]);
        }
    }

    /**
     * Gets the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return sprintf('%s.%s', $this->name, $this->extension);
    }

    /**
     * Gets the value of fullName.
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }
    
    /**
     * Sets the value of fullName.
     *
     * @param string $fullName the full name
     *
     * @return self
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
    
    /**
     * Sets the value of extension.
     *
     * @param string $extension the extension
     *
     * @return self
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Gets the value of length.
     *
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }
    
    /**
     * Sets the value of length.
     *
     * @param int $length the length
     *
     * @return self
     */
    public function setLength($length)
    {
        $this->length = (int) $length;

        return $this;
    }

    /**
     * Gets the value of changed.
     *
     * @return \DateTime
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * Gets the value of accessed.
     *
     * @return \DateTime
     */
    public function getAccessed()
    {
        return $this->accessed;
    }

    /**
     * Gets the value of content.
     *
     * @return string
     */
    public function getContent()
    {
        if ($this->exists && $this->content === null) {
            $this->content = file_get_contents($this->fullName);
        }

        return $this->content;
    }
    
    /**
     * Sets the value of content.
     *
     * @param string $content the content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Writes content data to file
     *
     * @return bool / int
     */
    public function write()
    {
        if ($this->exists && $this->content !== null) {
            return file_put_contents($this->fullName, $this->content);
        }

        return false;
    }

    /**
     * Gets the value of directory.
     *
     * @return DirectoryInfo
     */
    public function getDirectory()
    {
        return $this->directory;
    }
    
    /**
     * Sets the value of directory.
     *
     * @param DirectoryInfo $directory the directory
     *
     * @return self
     */
    public function setDirectory(DirectoryInfo $directory)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Gets the value of exists.
     *
     * @return bool
     */
    public function getExists()
    {
        return $this->exists;
    }
    
    /**
     * Sets the value of exists.
     *
     * @param bool $exists the exists
     *
     * @return self
     */
    public function setExists($exists)
    {
        $this->exists = (bool) $exists;

        return $this;
    }

    /**
     * Gets the value of isReadOnly.
     *
     * @return bool
     */
    public function getIsReadOnly()
    {
        return $this->isReadOnly;
    }
    
    /**
     * Sets the value of isReadOnly.
     *
     * @param bool $isReadOnly the is read only
     *
     * @return self
     */
    public function setIsReadOnly($isReadOnly)
    {
        $this->isReadOnly = (bool)$isReadOnly;

        return $this;
    }
}