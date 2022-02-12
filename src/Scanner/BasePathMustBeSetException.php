<?php

declare(strict_types=1);

namespace AnnotationsScanner\Scanner;

use RuntimeException;

class BasePathMustBeSetException extends RuntimeException
{
    public static function create(): self
    {
        return new self('Base path must be set');
    }
}
