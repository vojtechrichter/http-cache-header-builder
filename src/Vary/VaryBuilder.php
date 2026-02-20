<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\Vary;

final class VaryBuilder
{
    /** @var list<string> */
    private array $headers = [];

    private function __construct() {}

    public static function create(): self
    {
        return new self();
    }

    public function acceptEncoding(): self
    {
        return $this->header('Accept-Encoding');
    }

    public function accept(): self
    {
        return $this->header('Accept');
    }

    public function acceptLanguage(): self
    {
        return $this->header('Accept-Language');
    }

    public function authorization(): self
    {
        return $this->header('Authorization');
    }

    public function userAgent(): self
    {
        return $this->header('User-Agent');
    }

    public function origin(): self
    {
        return $this->header('Origin');
    }

    public function header(string $name): self
    {
        $this->headers[] = $name;

        return $this;
    }

    public function build(): VaryHeader
    {
        return VaryHeader::fromHeaders($this->headers);
    }
}
