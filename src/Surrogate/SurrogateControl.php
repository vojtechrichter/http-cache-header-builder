<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Surrogate;

final readonly class SurrogateControl
{
    public function __construct(
        private ?int $maxAge,
        private bool $noStore,
        private ?int $staleWhileRevalidate,
        private ?int $staleIfError,
    ) {
    }

    public static function maxAge(int $seconds): self
    {
        return new self(
            maxAge: $seconds,
            noStore: false,
            staleWhileRevalidate: null,
            staleIfError: null,
        );
    }

    public static function noStore(): self
    {
        return new self(
            maxAge: null,
            noStore: true,
            staleWhileRevalidate: null,
            staleIfError: null,
        );
    }

    public function staleWhileRevalidateWith(int $seconds): self
    {
        return new self(
            maxAge: $this->maxAge,
            noStore: $this->noStore,
            staleWhileRevalidate: $seconds,
            staleIfError: $this->staleIfError,
        );
    }

    public function staleIfErrorWith(int $seconds): self
    {
        return new self(
            maxAge: $this->maxAge,
            noStore: $this->noStore,
            staleWhileRevalidate: $this->staleWhileRevalidate,
            staleIfError: $seconds,
        );
    }

    public function toSurrogateControl(): string
    {
        return $this->render();
    }

    public function getHeaderName(SurrogateHeaderType $type = SurrogateHeaderType::SurrogateControl): string
    {
        return $type->value;
    }

    private function render(): string
    {
        if ($this->noStore) {
            return 'no-store';
        }

        $parts = [];

        if ($this->maxAge !== null) {
            $parts[] = 'max-age=' . $this->maxAge;
        }

        if ($this->staleWhileRevalidate !== null) {
            $parts[] = 'stale-while-revalidate=' . $this->staleWhileRevalidate;
        }

        if ($this->staleIfError !== null) {
            $parts[] = 'stale-if-error=' . $this->staleIfError;
        }

        return implode(', ', $parts);
    }
}
