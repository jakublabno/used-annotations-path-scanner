<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use AnnotationsScanner\Scanner\ScanMode\ScanMode;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;

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

        return new ScanResult(array_unique(array_merge(...$filesPaths)));
    }

    private function getUsedAnnotationsClassPaths(ReflectionMethod ...$reflectionMethod): array
    {
        $foundPaths = [];

        foreach ($reflectionMethod as $foundMethod) {
            foreach ($this->annotationsToSearchCollection as $lookedForAnnotation) {
                $foundAnnotationOnMethod = $this->annotationReader->getMethodAnnotation($foundMethod, $lookedForAnnotation);

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
        if (!class_exists($className, false)) {
            return null;
        }

        try {
            $reflectedClass = new ReflectionClass($className);
        } catch (Throwable $t) {
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
