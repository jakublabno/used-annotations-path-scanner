<?php

declare(strict_types=1);

namespace AnnotationsScanner\Tests\Fixture\Classes\AnnotatedWithThirdAnnotation;

use AnnotationsScanner\Tests\Fixture\Annotations\ThirdAnnotation;

class ClassWithAnnotatedMethodsThird
{
    /**
     * @ThirdAnnotation()
     */
    public function annotatedWithSecondAnnotation(): void
    {
    }
}
