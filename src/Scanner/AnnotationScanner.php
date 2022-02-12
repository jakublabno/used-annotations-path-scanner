<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

interface AnnotationScanner
{
    /**
     * @return string[]
     */
    public function scan(): array;
}
