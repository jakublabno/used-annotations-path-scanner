<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Scanner\ScanMode\ScanMode;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\Reader;
use ReflectionMethod;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

class DoctrineAnnotationsScanner extends AbstractAnnotationScanner implements AnnotationScanner
{
    private Reader $annotationReader;
    private AnnotationsToSearchCollection $annotationsToSearchCollection;
    private string $basePath;
    private string $scanMethod;

    public function __construct(
        AnnotationsToSearchCollection $annotationsToSearchCollection,
        string                        $basePath,
        string                        $scanMethod,
        ?string                       $excludeDirsRegex = null,
        Reader                        $reader = null
    ) {
        $this->annotationsToSearchCollection = $annotationsToSearchCollection;

        $this->basePath = $basePath;

        set_include_path(get_include_path() . PATH_SEPARATOR . $basePath);
        spl_autoload_extensions('.php');
        spl_autoload_register();

        $this->scanMethod = $scanMethod;

        $this->excludeDirectoriesRegex = $excludeDirsRegex;

        $this->annotationReader = $reader ?? call_user_func(function () {
                $parser = new DocParser();
                $parser->setIgnoreNotImportedAnnotations(true);

                return new AnnotationReader($parser);
            });
    }

    public function scan(ScanMode $mode = null): ScanResult
    {
        /**
         * @var $foundMethods \Roave\BetterReflection\Reflection\ReflectionMethod[]
         */
        $foundMethods = [];

        foreach ($this->getClassesNames() as $className) {
            if ($methods = $this->getMethods($className)) {
                $foundMethods[] = $methods;
            }
        }

        $filesPaths = [];

        foreach ($foundMethods as $foundMethod) {
            $filesPaths[] = $this->getUsedAnnotationsClassPaths(...$foundMethod);
        }

        return new ScanResult(array_unique(array_merge(...$filesPaths)));
    }

    private function getUsedAnnotationsClassPaths(\Roave\BetterReflection\Reflection\ReflectionMethod ...$reflectionMethod): array
    {
        $foundPaths = [];

        foreach ($reflectionMethod as $foundMethod) {
            foreach ($this->annotationsToSearchCollection as $lookedForAnnotation) {
                $nativeReflection = new ReflectionMethod($foundMethod->getDeclaringClass()->getName(), $foundMethod->getName());

                $foundAnnotationOnMethod = $this->annotationReader->getMethodAnnotation($nativeReflection, $lookedForAnnotation);

                if ($foundAnnotationOnMethod) {
                    $foundPaths[] = $foundMethod->getFileName();
                }
            }
        }

        return $foundPaths;
    }

    /**
     * @return ReflectionMethod[]
     */
    private function getMethods(string $className): ?array
    {
        if (!class_exists($className)) {
            return null;
        }

        try {
            $reflectedClass = ReflectionClass::createFromName($className);
        } catch (IdentifierNotFound $t) {
            return null;
        }

        return $reflectedClass->getMethods() ?: null;
    }

    /**
     * @return string[]
     */
    private function getClassesNames(): array
    {
        return $this->getClassFinder($this->scanMethod)->getClassesNames($this->basePath);
    }
}
