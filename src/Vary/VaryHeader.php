<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Vary;

use Vojtechrichter\HttpCacheHeaderBuilder\Exception\InvalidCacheDirectiveException;

final readonly class VaryHeader
{
    /** @var list<string> */
    private array $headers;

    public function __construct(
        array $headers,
    ) {
        $this->headers = array_values(array_unique($headers));
    }

    public static function fromHeaders(array $headers): self
    {
        if ($headers === []) {
            throw new \InvalidArgumentException('Vary header must contain at least one header name.');
        }

        return new self($headers);
    }

    public static function any(): self
    {
        return new self(['*']);
    }

    public function has(string $header): bool
    {
        return in_array($header, $this->headers, true);
    }

    /**
     * @return list<string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function toString(): string
    {
        return implode(', ', $this->headers);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
