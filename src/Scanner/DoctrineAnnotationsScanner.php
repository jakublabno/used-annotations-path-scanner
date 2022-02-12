<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use Composer\Autoload\ClassMapGenerator;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class DoctrineAnnotationsScanner implements AnnotationScanner
{
    private Reader $annotationReader;
    private AnnotationsToSearchCollection $annotationsToSearchCollection;
    private string $basePath;

    public function __construct(AnnotationsToSearchCollection $annotationsToSearchCollection, string $basePath, Reader $reader = null)
    {
        $this->annotationsToSearchCollection = $annotationsToSearchCollection;

        $this->basePath = $basePath;

        $this->annotationReader = $reader ?? new AnnotationReader();
    }

    public function scan(): array
    {
        /**
         * @var $foundMethods ReflectionMethod[]
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

        return array_unique(array_merge(...$filesPaths));
    }

    private function getUsedAnnotationsClassPaths(ReflectionMethod ...$reflectionMethod): array
    {
        $foundPaths = [];

        foreach ($reflectionMethod as $foundMethod) {
            foreach ($this->annotationsToSearchCollection as $lookedForAnnotation) {
                $foundAnnotationOnMethod = $this->annotationReader->getMethodAnnotation($foundMethod, $lookedForAnnotation);

                if ($foundAnnotationOnMethod) {
                    $foundPaths[] = $foundMethod->getDeclaringClass()->getFileName();
                }
            }
        }

        return $foundPaths;
    }

    /**
     * @return ReflectionMethod[]
     * @throws ReflectionException
     */
    private function getMethods(string $className): ?array
    {
        $reflectedClass = new ReflectionClass($className);

        return $reflectedClass->getMethods() ?: null;
    }

    /**
     * @return string[]
     */
    private function getClassesNames(): array
    {
        return array_keys(ClassMapGenerator::createMap($this->basePath));
    }
}
