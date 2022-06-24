<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\AbstractSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\SingleFileSourceLocator;

class ClassNameFromPathGeneratorReflectionBased
{
    private const OLD_VERSION_REFLECTOR_CLASS = 'Roave\BetterReflection\Reflector\ClassReflector';
    private const OLD_VERSION_REFLECT_CLASSES_METHOD = 'getAllClasses';

    private const NEW_VERSION_REFLECTOR_CLASS = 'Roave\BetterReflection\Reflector\DefaultReflector';
    private const NEW_VERSION_REFLECT_CLASSES_METHOD = 'reflectAllClasses';

    public static function getFullClassNameFromFile(string $path): ?string
    {
        $astLocator = (new BetterReflection())->astLocator();
        $locator = new SingleFileSourceLocator($path, $astLocator);
        $classes = self::getClasses($locator);

        if (!empty($classes)) {
            $class = $classes[0];

            return $class->getName();
        }

        return null;
    }

    /**
     * @return list<ReflectionClass>
     */
    private static function getClasses(AbstractSourceLocator $locator): iterable
    {
        if (class_exists(self::OLD_VERSION_REFLECTOR_CLASS)) {
            $locatorClass = self::OLD_VERSION_REFLECTOR_CLASS;
            $reflectAllClassesMethod = self::OLD_VERSION_REFLECT_CLASSES_METHOD;
        } else {
            $locatorClass = self::NEW_VERSION_REFLECTOR_CLASS;
            $reflectAllClassesMethod = self::NEW_VERSION_REFLECT_CLASSES_METHOD;
        }

        return (new $locatorClass($locator))->$reflectAllClassesMethod();
    }
}
