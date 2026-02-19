<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\CacheControl;

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

    private static function validate(DirectiveMap $directiveMap): void
    {

    }
}
