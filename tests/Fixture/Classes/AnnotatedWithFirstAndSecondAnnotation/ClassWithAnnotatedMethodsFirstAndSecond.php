<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithFirstAndSecondAnnotation;

use AnnotationsScanner\Tests\Fixture\Annotations\FirstAnnotation;
use AnnotationsScanner\Tests\Fixture\Annotations\SecondAnnotation;

class ClassWithAnnotatedMethodsFirstAndSecond
{
    /**
     * @FirstAnnotation()
     */
    public function annotatedWithFirstAnnotation(): void
    {
    }

    /**
     * @SecondAnnotation()
     */
    public function annotatedWithSecondAnnotation(): void
    {
    }
}
