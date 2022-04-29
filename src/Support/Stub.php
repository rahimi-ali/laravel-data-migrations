<?php

namespace AliRahimiCoder\LaravelDataMigrations\Support;

class Stub
{
    /**
     * The stub path.
     */
    protected string $path;

    /**
     * The replacements array.
     *
     * @var array<string, string|int>
     */
    protected array $replaces = [];

    /**
     * @param string $path
     * @param array<string, string|int> $replaces
     */
    public function __construct(string $path, array $replaces = [])
    {
        $this->path = $path;
        $this->replaces = $replaces;
    }

    /**
     * @param string $path
     * @param array<string, string|int> $replaces
     * @return self
     */
    public static function create(string $path, array $replaces = []): self
    {
        return new static($path, $replaces);
    }

    /**
     * @param string $path
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return  __DIR__.'/../Commands/stubs'.$this->path;
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function getContents(): string
    {
        $contents = file_get_contents($this->getPath());

        foreach ($this->replaces as $search => $replace) {
            $contents = str_replace('$'.strtoupper($search).'$', $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get stub contents.
     *
     * @return string
     */
    public function render(): string
    {
        return $this->getContents();
    }

    /**
     * Save stub to specific path.
     *
     * @param string $path
     * @param string $filename
     *
     * @return int|false
     */
    public function saveTo(string $path, string $filename): int|false
    {
        return file_put_contents($path.'/'.$filename, $this->getContents());
    }

    /**
     * Set replacements array.
     *
     * @param array<string, string|int> $replaces
     *
     * @return $this
     */
    public function replace(array $replaces = []): self
    {
        $this->replaces = $replaces;

        return $this;
    }

    /**
     * @return array<string, string|int>
     */
    public function getReplaces(): array
    {
        return $this->replaces;
    }

    /**
     * Handle magic method __toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}