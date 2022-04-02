<?php

declare(strict_types=1);

namespace AnnotationsScanner\Finder;

class ClassNameFromPathGenerator
{
    private const TOKEN_NAMESPACE = 'T_NAMESPACE';
    private const TOKEN_CLASSNAME = 'T_CLASS';

    public static function getFullClassNameFromFile(string $path): ?string
    {
        if (is_dir($path)) {
            return null;
        }

        $file = file_get_contents($path);
        $tokens = token_get_all($file, TOKEN_PARSE);

        $namespaceAtLine = null;
        $classNameAtLine = null;

        foreach ($tokens as $token) {
            if (!is_array($token)) {
                continue;
            }

            list($id, $name, $line) = $token;

            if (token_name($id) == self::TOKEN_NAMESPACE) {
                $namespaceAtLine = $line;
            }

            if (token_name($id) == self::TOKEN_CLASSNAME) {
                $classNameAtLine = $line;
            }
        }

        if ($namespaceAtLine && $classNameAtLine) {
            $splittedFile = explode(PHP_EOL, $file);

            $namespace = rtrim(ltrim($splittedFile[$namespaceAtLine - 1], 'namespace '), ';');
            $className = rtrim(ltrim($splittedFile[$classNameAtLine - 1], 'class '), ';');

            return $namespace . '\\' . $className;
        }

        return null;
    }
}
