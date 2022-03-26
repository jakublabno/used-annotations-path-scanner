<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Finder\ClassFinder;
use AnnotationsScanner\Finder\ComposerClassFinder;
use AnnotationsScanner\Finder\DirectoryIteratorClassFinder;

class AbstractAnnotationScanner
{
    protected ?string $excludeDirectoriesRegex = null;

    protected function getClassFinder(string $scanMethod): ClassFinder
    {
        if ($scanMethod == ScanMethod::DIRECTORY_ITERATOR) {
            $scanner = new DirectoryIteratorClassFinder();

            if ($this->excludeDirectoriesRegex) {
                $scanner->setExcludeRegex($this->excludeDirectoriesRegex);
            }

            return $scanner;
        }

        return new ComposerClassFinder();
    }
}
