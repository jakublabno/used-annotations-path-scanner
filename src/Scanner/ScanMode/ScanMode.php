<?php

declare(strict_types=1);

namespace Scanner\AnnotationsScanner;

interface ScanMode
{
    public function getName(): string;
}
