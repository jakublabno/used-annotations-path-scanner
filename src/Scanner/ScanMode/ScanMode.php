<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner\ScanMode;

interface ScanMode
{
    public function getName(): string;
}
