<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Middleware;


/**
 * Matches a URI path against a glob-like pattern.
 *
 * Supports:
 *   /assets/*     → matches /assets/anything (one segment)
 *   /assets/**    → matches /assets/any/depth/here
 *   /api/v1/users → exact match
 */
final readonly class RoutePattern
{
    private string $regex;

    public function __construct(
        public string $pattern,
    ) {
        $this->regex = self::compile($this->pattern);
    }

    public function matches(string $path): bool
    {
        return preg_match($this->regex, $path) === 1;
    }

    private static function compile(string $pattern): string
    {
        if (!str_contains($pattern, '*')) {
            return '#^' . preg_quote($pattern, '#') . '$#';
        }

        $quoted = preg_quote($pattern, '*');

        $regex = str_replace(
            ['\\*\\', '\\*'],
            ['.*', '[^/]*'],
            $quoted
        );

        return '#^' . $regex . '$#';
    }
}
