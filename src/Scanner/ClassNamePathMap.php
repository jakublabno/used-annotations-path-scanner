<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

class ClassNamePathMap
{
    public string $name;
    public string $path;

    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }
}
