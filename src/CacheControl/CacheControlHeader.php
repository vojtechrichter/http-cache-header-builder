<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\CacheControl;

use Vojtechrichter\HttpCacheHeaderBuilder\Exception\InvalidCacheDirectiveException;

final readonly class CacheControlHeader
{
    private function __construct(
        private DirectiveMap $directiveMap,
    ) {
    }

    public static function fromDirectiveMap(DirectiveMap $directiveMap): self
    {
        /**
         * Validate against a clone so the caller can't mutate
         * the original object after construction
         */
        $frozen = $directiveMap->clone();
        self::validate($frozen);

        return new self($directiveMap);
    }

    public static function immutableAsset(): self
    {
        return new self(
            new DirectiveMap()
                ->set(Directive::Public, true)
                ->set(Directive::MaxAge, 31536000)
                ->set(Directive::Immutable, true)
        );
    }

    public static function noStore(): self
    {
        return new self(
            new DirectiveMap()
                ->set(Directive::NoStore, true)
        );
    }

    public static function revalidate(): self
    {
        return new self(
            new DirectiveMap()
                ->set(Directive::NoCache, true)
        );
    }

    public static function cdnOnly(): self
    {
        return new self(
            new DirectiveMap()
                ->set(Directive::Private, true)
                ->set(Directive::NoCache, true)
        );
    }

    public function has(Directive $directive): bool
    {
        return $this->directiveMap->has($directive);
    }

    public function get(Directive $directive): int|bool|null
    {
        return $this->directiveMap->get($directive);
    }

    public function getMaxAge(): ?int
    {
        $value = $this->directiveMap->get(Directive::MaxAge);

        return is_int($value) ? $value : null;
    }

    public function getSMaxAge(): ?int
    {
        $value = $this->directiveMap->get(Directive::MaxAge);

        return is_int($value) ? $value : null;
    }

    public function isPublic(): bool
    {
        return $this->directiveMap->has(Directive::Public);
    }

    public function isPrivate(): bool
    {
        return $this->directiveMap->has(Directive::Private);
    }

    public function toString(): string
    {
        $parts = [];

        foreach ($this->directiveMap->entries() as $entry) {
            if ($entry['value'] === true) {
                $parts[] = $entry['directive']->value;
            } elseif (is_int($entry['value'])) {
                $parts[] = $entry['directive']->value . '=' . $entry['value'];
            }
        }

        return implode(', ', $parts);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private static function validate(DirectiveMap $directiveMap): void
    {
        if ($directiveMap->has(Directive::Public) && $directiveMap->has(Directive::Private)) {
            throw InvalidCacheDirectiveException::publicWithPrivate();
        }

        if ($directiveMap->has(Directive::NoStore) && $directiveMap->has(Directive::MaxAge)) {
            throw InvalidCacheDirectiveException::noStoreWithMaxAge();
        }

        if ($directiveMap->has(Directive::NoStore) && $directiveMap->has(Directive::SMaxAge)) {
            throw InvalidCacheDirectiveException::noStoreWithSMaxAge();
        }

        if ($directiveMap->has(Directive::Private) && $directiveMap->has(Directive::SMaxAge)) {
            throw InvalidCacheDirectiveException::sMaxAgeWithPrivate();
        }

        if ($directiveMap->has(Directive::StaleWhileRevalidate) && !$directiveMap->has(Directive::MaxAge)) {
            throw InvalidCacheDirectiveException::staleWhileRevalidateWithoutMaxAge();
        }

        if ($directiveMap->has(Directive::StaleIfError) && !$directiveMap->has(Directive::MaxAge)) {
            throw InvalidCacheDirectiveException::staleIfErrorWithoutMaxAge();
        }

        if ($directiveMap->has(Directive::Immutable) && !$directiveMap->has(Directive::MaxAge)) {
            throw InvalidCacheDirectiveException::immutableWithoutMaxAge();
        }
    }
}
