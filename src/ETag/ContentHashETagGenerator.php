<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\ETag;

final readonly class ContentHashETagGenerator implements ETagGeneratorInterface
{
    private const string HASHING_ALGO = 'xxh3';

    /**
     * @param string $content
     * @return non-empty-string
     */
    public function generate(string $content): string
    {
        return sprintf('"%s"', hash(self::HASHING_ALGO, $content));
    }
}
