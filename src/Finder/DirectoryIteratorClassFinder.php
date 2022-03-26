<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class DirectoryIteratorClassFinder implements ClassFinder
{
    public function getClassesNames(string $basePath): array
    {
        $directory = new RecursiveDirectoryIterator($basePath, FilesystemIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directory);

        $classesNames = [];

        foreach ($iterator as $file) {
            if ($className = $this->getClassNameFromFile($file->__toString())) {
                $classesNames[] = $className;
            }
        }

        return $classesNames;
    }

    private function getClassNameFromFile(string $fileName): ?string
    {
        return ClassNameFromPathGenerator::getFullClassNameFromFile($fileName);
    }
}
