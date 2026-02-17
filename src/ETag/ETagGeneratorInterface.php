<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\ETag;

interface ETagGeneratorInterface
{
    public function generate(string $content): string;
}
