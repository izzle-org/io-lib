<?php
namespace Izzle\IO;

use DateTime;
use Exception;
use InvalidArgumentException;
use Izzle\IO\Exception\FileNotFoundException;

class FileInfo
{
    protected string $fullName;
    protected string $name;
    protected string $extension;
    protected int $length;
    protected ?DateTime $changed = null;
    protected ?DateTime $accessed = null;
    protected ?string $content = null;
    protected ?DirectoryInfo $directory = null;
    protected bool $exists;
    protected bool $isReadOnly;

    /**
     * @param string $path
     * @param bool|true $directory
     * @throws Exception
     */
    public function __construct(string $path, bool $directory = true)
    {
        if (preg_match('/[\,\<\>\|]/', $path)) {
            throw new InvalidArgumentException('invalid path characters');
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
     * @throws Exception
     */
    public function create(): bool
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
    public function delete(): bool
    {
        if ($this->exists) {
            return unlink($this->fullName);
        }

        throw new FileNotFoundException("file '{$this->fullName}' not found");
    }

    /**
     * @param string $name
     * @return bool
     * @throws FileNotFoundException
     */
    public function rename(string $name): bool
    {
        if ($this->exists && $this->getDirectory() !== null) {
            $newPath = Path::combine($this->getDirectory()->getFullName(), $name);
            if (rename($this->fullName, $newPath)) {
                $this->name = $name;
                $this->fullName = $newPath;

                return true;
            }

            return false;
        }

        throw new FileNotFoundException("file '{$this->fullName}' not found");
    }

    /**
     * @param string $name
     * @return bool
     * @throws FileNotFoundException
     */
    public function move(string $name): bool
    {
        if ($this->exists) {
            if (rename($this->fullName, $name)) {
                $this->name = basename($name);
                $this->fullName = $name;

                return true;
            }

            return false;
        }

        throw new FileNotFoundException("file '{$this->fullName}' not found");
    }

    /**
     * @throws Exception
     */
    protected function getInfos(): void
    {
        if ($this->exists) {
            $this->setFullName($this->fullName)
                ->setIsReadOnly(!is_readable($this->fullName));

            $fileStats = stat($this->fullName);
            $this->setLength($fileStats['size']);
            $this->changed = new DateTime('@' . $fileStats['mtime']);
            $this->accessed = new DateTime('@' . $fileStats['atime']);
        }
    }

    /**
     * Gets the filename.
     *
     * @return string
     */
    public function getFilename(): string
    {
        return sprintf('%s.%s', $this->name, $this->extension);
    }

    /**
     * Gets the value of fullName.
     *
     * @return string
     */
    public function getFullName(): string
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
    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName(): string
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
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of extension.
     *
     * @return string
     */
    public function getExtension(): string
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
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Gets the value of length.
     *
     * @return int
     */
    public function getLength(): int
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
    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Gets the value of changed.
     *
     * @return DateTime|null
     */
    public function getChanged(): ?DateTime
    {
        return $this->changed;
    }

    /**
     * Gets the value of accessed.
     *
     * @return DateTime|null
     */
    public function getAccessed(): ?DateTime
    {
        return $this->accessed;
    }

    /**
     * Gets the value of content.
     *
     * @return string|null
     */
    public function getContent(): ?string
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
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Writes content data to file
     *
     * @return bool
     */
    public function write(): bool
    {
        if ($this->exists) {
            return file_put_contents($this->fullName, $this->content) !== false;
        }

        return false;
    }

    /**
     * Gets the value of directory.
     *
     * @return DirectoryInfo|null
     */
    public function getDirectory(): ?DirectoryInfo
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
    public function setDirectory(DirectoryInfo $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Gets the value of exists.
     *
     * @return bool
     */
    public function getExists(): bool
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
    public function setExists(bool $exists): self
    {
        $this->exists = $exists;

        return $this;
    }

    /**
     * Gets the value of isReadOnly.
     *
     * @return bool
     */
    public function getIsReadOnly(): bool
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
    public function setIsReadOnly(bool $isReadOnly): self
    {
        $this->isReadOnly = $isReadOnly;

        return $this;
    }
}
