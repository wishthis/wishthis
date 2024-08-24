<?php

namespace ConventionalChangelog\Type;

abstract class PackageBump
{
    /**
     * Package filename.
     *
     * @var string
     */
    protected $fileName;
    /**
     * Package lock files.
     *
     * @var array
     */
    protected $lockFiles;
    /**
     * Package file type.
     *
     * @var string
     */
    protected $fileType;
    /**
     * Base path.
     *
     * @var string
     */
    protected $path;
    /**
     * Content.
     *
     * @var mixed
     */
    protected $content;
    /**
     * Original content for backup usage.
     *
     * @var mixed
     */
    protected $originContent;

    /**
     * Bump constructor.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        if ($this->exists()) {
            $raw = file_get_contents($this->getFilePath());
            switch ($this->fileType) {
                case 'json':
                    $this->originContent = json_decode($raw);
                    if ($this->originContent === null && json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception(json_last_error_msg(), json_last_error());
                    }
                    $this->content = clone $this->originContent;
                    break;
                default:
                    $this->originContent = $raw;
                    $this->content = $raw;
            }
        }
    }

    /**
     * Get version.
     */
    public function getVersion(): ?string
    {
        return null;
    }

    /**
     * Set version.
     *
     * @return $this
     */
    public function setVersion(string $version)
    {
        return $this;
    }

    /**
     * Get root path.
     */
    public function getPath(): string
    {
        if (empty($this->path)) {
            $this->path = getcwd();
        }

        return $this->path;
    }

    /**
     * Get file path.
     */
    public function getFilePath(): string
    {
        $path = $this->getPath() . DIRECTORY_SEPARATOR . $this->fileName;

        return preg_replace('/' . preg_quote(DIRECTORY_SEPARATOR, '/') . '+/', DIRECTORY_SEPARATOR, $path);
    }

    /**
     * Get filename.
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Get all existing lock files.
     */
    public function getExistingLockFiles(): array
    {
        $paths = [];
        foreach ($this->lockFiles as $lockFile) {
            $path = $this->getPath() . DIRECTORY_SEPARATOR . $lockFile;
            $path = preg_replace('/' . preg_quote(DIRECTORY_SEPARATOR, '/') . '+/', DIRECTORY_SEPARATOR, $path);
            if (is_file($path)) {
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * Get lock file name.
     */
    public function getLockFiles(): array
    {
        return $this->lockFiles;
    }

    /**
     * Package file exists.
     */
    public function exists(): bool
    {
        return is_file($this->getFilePath());
    }

    /**
     * Save content.
     *
     * @return $this
     */
    public function save()
    {
        if ($this->exists()) {
            switch ($this->fileType) {
                case 'json':
                    $content = json_encode($this->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    if ($content === null && json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception(json_last_error_msg(), json_last_error());
                    }
                    break;
                default:
                    $content = $this->content;
            }
            file_put_contents($this->getFilePath(), $content);
        }

        return $this;
    }

    /**
     * Set content.
     *
     * @param mixed $content
     *
     * @return $this
     */
    protected function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content and back backup.
     *
     * @return mixed
     */
    protected function getContent()
    {
        return $this->content;
    }
}
