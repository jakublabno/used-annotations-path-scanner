<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

interface ClassFinder
{
    public function getClassesNames(string $basePath): array;
}
