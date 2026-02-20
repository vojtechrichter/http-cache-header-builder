<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Surrogate;

enum SurrogateHeaderType: string
{
    /** W3C standard */
    case SurrogateControl = 'Surrogate-Control';

    /** RFC 9213, newer standard */
    case CdnCacheControl = 'CDN-Cache-Control';
}
