<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

class ClassNameFromPathGeneratorReflectionBased
{
    public static function getFullClassNameFromFile(string $path): ?string
    {
        $astLocator = (new BetterReflection())->astLocator();
        $locator = new SingleFileSourceLocator($path, $astLocator);
        $classes = (new ClassReflector($locator))->getAllClasses();

        if (!empty($classes)) {
            $class = $classes[0];

            return $class->getName();
        }

        return null;
    }
}
