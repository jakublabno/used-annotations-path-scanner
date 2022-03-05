<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Finder\ClassFinder;
use AnnotationsScanner\Finder\ComposerClassFinder;

class AbstractAnnotationScanner
{
    protected function getClassFinder(): ClassFinder
    {
        return new ComposerClassFinder();
    }
}
