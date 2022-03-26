<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Unit;

use AnnotationsScanner\Scanner\AnnotationsToSearchCollection;
use AnnotationsScanner\Scanner\DoctrineAnnotationsScanner;
use AnnotationsScanner\Scanner\ScanMethod;
use AnnotationsScanner\Tests\Fixture\Annotations\FirstAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\SecondAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\ThirdAnnotation;
use AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithFirstAndSecondAnnotation\ClassWithAnnotatedMethodsFirstAndSecond;
use AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithThirdAnnotation\ClassWithAnnotatedMethodsThird;
use ArrayIterator;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DoctrineAnnotationsScannerTest extends TestCase
{
    /**
     * @test
     * @dataProvider getScanModes
     */
    public function find_paths_only_pointed_annotations_from_directory(string $scanMode): void
    {
        $scanner = $this->giveScannerWithoutCache('/app/tests/Fixture', $scanMode, FirstAnnotation::class);

        $foundPaths = $scanner->scan()->getFilePaths();

        $expectedPath = $this->getPathOfClass(ClassWithAnnotatedMethodsFirstAndSecond::class);
        $this->assertCount(1, $foundPaths);
        $this->assertContains($expectedPath, $foundPaths);
    }

    /**
     * @test
     * @dataProvider getScanModes
     */
    public function find_dirs_only_pointed_annotations_from_directory(string $scanMode): void
    {
        $scanner = $this->giveScannerWithoutCache('/app/tests/Fixture', $scanMode, FirstAnnotation::class);

        $foundPaths = $scanner->scan()->getFileDirs();

        $expectedPath = '/app/tests/Fixture/Classes/AnnotatedWithFirstAndSecondAnnotation';
        $this->assertCount(1, $foundPaths);
        $this->assertContains($expectedPath, $foundPaths);
    }

    /**
     * @test
     * @dataProvider getScanModes
     */
    public function find_paths_only_first_and_second_annotations_from_strict_directory(string $scanMode): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/AnnotatedWithFirstAndSecondAnnotation',
            $scanMode,
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
     * @dataProvider getScanModes
     */
    public function find_paths_only_third_annotations_from_strict_directory(string $scanMode): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/AnnotatedWithThirdAnnotation',
            $scanMode,
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
     * @dataProvider getScanModes
     */
    public function return_empty_array_when_no_annotations_found(string $scanMode): void
    {
        $scanner = $this->giveScannerWithoutCache(
            '/app/tests/Fixture/Classes/Empty',
            $scanMode,
            FirstAnnotation::class,
            SecondAnnotation::class,
            ThirdAnnotation::class,
        );

        $foundPaths = $scanner->scan()->getFilePaths();

        $this->assertEmpty($foundPaths);
    }

    public function getScanModes(): Generator
    {
        yield [ScanMethod::DIRECTORY_ITERATOR];
        yield [ScanMethod::COMPOSER];
    }

    private function giveScannerWithoutCache(string $basePath, string $scanMode, string ...$annotations): DoctrineAnnotationsScanner
    {
        return new DoctrineAnnotationsScanner(
            new AnnotationsToSearchCollection(new ArrayIterator($annotations)),
            $basePath,
            $scanMode,
        );
    }

    private function getPathOfClass(string $className): string
    {
        return (new ReflectionClass($className))->getFileName();
    }
}
