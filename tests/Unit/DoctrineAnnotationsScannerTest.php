<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Unit;

use AnnotationsScanner\Scanner\AnnotationsToSearchCollection;
use AnnotationsScanner\Scanner\DoctrineAnnotationsScanner;
use AnnotationsScanner\Tests\Fixture\Annotations\FirstAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\SecondAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\ThirdAnnotation;
use AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithFirstAndSecondAnnotation\ClassWithAnnotatedMethodsFirstAndSecond;
use AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithThirdAnnotation\ClassWithAnnotatedMethodsThird;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DoctrineAnnotationsScannerTest extends TestCase
{
    /**
     * @test
     */
    public function find_only_pointed_annotations_from_directory(): void
    {
        $scanner = $this->giveScannerWithoutCache('/app/tests/Fixture', FirstAnnotation::class);

        $foundPaths = $scanner->scan()->getFilePaths();

        $expectedPath = $this->getPathOfClass(ClassWithAnnotatedMethodsFirstAndSecond::class);
        $this->assertCount(1, $foundPaths);
        $this->assertContains($expectedPath, $foundPaths);
    }

    /**
     * @test
     */
    public function find_only_first_and_second_annotations_from_strict_directory(): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/AnnotatedWithFirstAndSecondAnnotation',
            FirstAnnotation::class,
            SecondAnnotation::class,
            ThirdAnnotation::class,
        );

        $foundPaths = $scanner->scan()->getFilePaths();

        $expectedPath = $this->getPathOfClass(ClassWithAnnotatedMethodsFirstAndSecond::class);
        $this->assertCount(1, $foundPaths);
        $this->assertContains($expectedPath, $foundPaths);
    }

    /**
     * @test
     */
    public function find_only_third_annotations_from_strict_directory(): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/AnnotatedWithThirdAnnotation',
            FirstAnnotation::class,
            SecondAnnotation::class,
            ThirdAnnotation::class,
        );

        $foundPaths = $scanner->scan()->getFilePaths();

        $expectedPath = $this->getPathOfClass(ClassWithAnnotatedMethodsThird::class);
        $this->assertCount(1, $foundPaths);
        $this->assertContains($expectedPath, $foundPaths);
    }

    /**
     * @test
     */
    public function return_empty_array_when_no_annotations_found(): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/Empty',
            FirstAnnotation::class,
            SecondAnnotation::class,
            ThirdAnnotation::class,
        );

        $foundPaths = $scanner->scan()->getFilePaths();

        $this->assertEmpty($foundPaths);
    }

    private function giveScannerWithoutCache(string $basePath, string ...$annotations): DoctrineAnnotationsScanner
    {
        return new DoctrineAnnotationsScanner(
            new AnnotationsToSearchCollection(new ArrayIterator($annotations)),
            $basePath,
        );
    }

    private function getPathOfClass(string $className): string
    {
        return (new ReflectionClass($className))->getFileName();
    }
}
