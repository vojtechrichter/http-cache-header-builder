<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\CacheControl;

final class CacheControlBuilder
{
    private DirectiveMap $directiveMap;

    public function __construct(
    ) {
        $this->directiveMap = new DirectiveMap();
    }

    public static function create(): self
    {
        return new self();
    }

    public function public(): self
    {
        $this->directiveMap->set(Directive::Public, true);

        return $this;
    }

    public function private(): self
    {
        $this->directiveMap->set(Directive::Private, true);

        return $this;
    }

    public function maxAge(int $seconds): self
    {
        $this->directiveMap->set(Directive::MaxAge, $seconds);

        return $this;
    }

    public function sMaxAge(int $seconds): self
    {
        $this->directiveMap->set(Directive::MaxAge, $seconds);

        return $this;
    }

    public function noCache(): self
    {
        $this->directiveMap->set(Directive::NoCache, true);

        return $this;
    }

    public function noStore(): self
    {
        $this->directiveMap->set(Directive::NoStore, true);

        return $this;
    }

    public function mustRevalidate(): self
    {
        $this->directiveMap->set(Directive::MustRevalidate, true);

        return $this;
    }

    public function proxyRevalidate(): self
    {
        $this->directiveMap->set(Directive::ProxyRevalidate, true);

        return $this;
    }

    public function staleWhileRevalidate(int $seconds): self
    {
        $this->directiveMap->set(Directive::StaleWhileRevalidate, $seconds);

        return $this;
    }

    public function staleIfError(int $seconds): self
    {
        $this->directiveMap->set(Directive::StaleIfError, $seconds);

        return $this;
    }

    public function immutable(): self
    {
        $this->directiveMap->set(Directive::Immutable, true);

        return $this;
    }

    public function noTransform(): self
    {
        $this->directiveMap->set(Directive::NoTransform, true);

        return $this;
    }

    public function mustUnderstand(): self
    {
        $this->directiveMap->set(Directive::MustUnderstand, true);

        return $this;
    }

    public function build(): CacheControlHeader
    {
        return CacheControlHeader::fromDirectiveMap($this->directiveMap);
    }
}
