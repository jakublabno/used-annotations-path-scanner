<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

use Composer\Autoload\ClassMapGenerator;

class ComposerClassFinder implements ClassFinder
{
    public function getClassesNames(string $basePath): array
    {
        return array_keys(ClassMapGenerator::createMap($basePath));
    }
}
