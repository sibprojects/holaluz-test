<?php

namespace App\Luz\Domain\Reader;

abstract class Reader
{
    protected $filename;
    protected $content;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function read()
    {
        $this->content = file_get_contents($this->filename);
    }

    abstract function prepare();
}