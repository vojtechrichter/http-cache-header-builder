<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Exception;

final  class InvalidCacheDirectiveException extends \LogicException
{
    public static function publicWithPrivate(): self
    {
        return new self(
            'public and private directives are mutually exclusive.' .
            'public allows shared caches (CDNs, proxies), to store the response, ' .
            'while private restricts it to the end user\'s browser only.',
        );
    }

    public static function noStoreWithMaxAge(): self
    {
        return new self(
            'no-store cannot be combined with max-age. ' .
            'no-store instructs caches to not store the response at all, ' .
            'so a shared cache TLL is meaningless.',
        );
    }

    public static function noStoreWithSMaxAge(): self
    {
        return new self(
            'no-store cannot be combined with s-maxage. '
            . 'no-store instructs caches to not store the response at all, '
            . 'so a shared cache TTL is meaningless.',
        );
    }

    public static function sMaxAgeWithPrivate(): self
    {
        return new self(
            's-maxage cannot be used with private. '
            . 's-maxage controls shared caches (CDNs, proxies), '
            . 'but private explicitly excludes them. '
            . 'To set CDN TTL separately from browser TTL, '
            . 'use Surrogate-Control or CDN-Cache-Control headers instead.',
        );
    }

    public static function staleWhileRevalidateWithoutMaxAge(): self
    {
        return new self(
            'stale-while-revalidate requires max-age. '
            . 'Without a baseline TTL, there is no point at which '
            . 'the response becomes stale and revalidation would begin.',
        );
    }

    public static function staleIfErrorWithoutMaxAge(): self
    {
        return new self(
            'stale-if-error requires max-age. '
            . 'Without a baseline TTL, there is no stale state '
            . 'that would trigger the stale-if-error behavior.',
        );
    }

    public static function immutableWithoutMaxAge(): self
    {
        return new self(
            'immutable requires max-age. '
            . 'immutable tells the browser to never revalidate during the TTL window, '
            . 'but without max-age there is no TTL window to speak of.',
        );
    }
}
