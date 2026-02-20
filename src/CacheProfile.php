<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder;

use Vojtechrichter\HttpCacheHeaderBuilder\CacheControl\CacheControlHeader;
use Vojtechrichter\HttpCacheHeaderBuilder\Surrogate\SurrogateControl;
use Vojtechrichter\HttpCacheHeaderBuilder\Surrogate\SurrogateHeaderType;
use Vojtechrichter\HttpCacheHeaderBuilder\Vary\VaryHeader;

final readonly class CacheProfile
{
    public function __construct(
        public CacheControlHeader $cacheControlHeader,
        public ?VaryHeader $varyHeader = null,
        public ?SurrogateControl $surrogateControl = null,
        public SurrogateHeaderType $surrogateHeaderType = SurrogateHeaderType::SurrogateControl,
    ) {
    }
}
