<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Finder\ClassFinder;
use AnnotationsScanner\Finder\ComposerClassFinder;
use AnnotationsScanner\Finder\DirectoryIteratorClassFinder;

class AbstractAnnotationScanner
{
    protected function getClassFinder(string $scanMethod): ClassFinder
    {
        if ($scanMethod == ScanMethod::DIRECTORY_ITERATOR) {
            return new DirectoryIteratorClassFinder();
        }

        return new ComposerClassFinder();
    }
}
