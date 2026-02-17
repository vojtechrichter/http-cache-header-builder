<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\CacheControl;

enum Directive: string
{
    case Public = 'public';
    case Private = 'private';
    case NoCache = 'no-cache';
    case NoStore = 'no-store';
    case MaxAge = 'max-age';
    case SMaxAge = 'x-maxage';
    case MustRevalidate = 'must-revalidate';
    case ProxyRevalidate = 'proxy-revalidate';
    case MustUnderstand = 'must-understand';
    case NoTransform = 'no-transform';
    case Immutable = 'immutable';
    case StaleWhileRevalidate = 'stale-while-revalidate';
    case StaleIfError = 'stale-if-error';
}
