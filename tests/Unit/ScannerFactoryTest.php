<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Unit;

use AnnotationsScanner\Scanner\ScannerFactory;
use AnnotationsScanner\Tests\Fixture\Annotations\FirstAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\ThirdAnnotation;
use AnnotationsScanner\Tests\Mother\FakeCacheMother;
use PHPUnit\Framework\TestCase;

class ScannerFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function use_provided_cache(): void
    {
        $expectedResult = [
            '/app/tests/Fixture/Classes/AnnotatedWithThirdAnnotation/ClassWithAnnotatedMethodsThird.php',
        ];
        $cache = FakeCacheMother::withResult($expectedResult);
        $scanner = ScannerFactory::createWithDefaultReaderAndCache(
            '/app/tests/Fixture/Classes/AnnotatedWithFirstAndSecondAnnotation',
            $cache,
            FirstAnnotation::class,
            ThirdAnnotation::class,
        );

        $found = $scanner->scan();

        $this->assertSame($expectedResult, $found);
    }
}
