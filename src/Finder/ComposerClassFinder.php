<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

use Composer\Autoload\ClassMapGenerator;

class ComposerClassFinder implements ClassFinder
{
    use ExcludeTrait;

    public function getClassesNames(string $basePath): array
    {
        $map = $this->excludeRegex
            ? ClassMapGenerator::createMap($basePath, $this->excludeRegex)
            : ClassMapGenerator::createMap($basePath);

        return array_keys($map);
    }
}
