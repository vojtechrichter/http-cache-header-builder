<?php

declare(strict_types=1);

namespace Vojtechrichter\HttpCacheHeaderBuilder\CacheControl;

final class DirectiveMap
{
    /** @var array<string, int|bool> */
    private array $items = [];

    public function set(Directive $directive, int|bool $value): self
    {
        $this->items[$directive->value] = $value;

        return $this;
    }

    public function has(Directive $directive): bool
    {
        return array_key_exists($directive->value, $this->items);
    }

    public function get(Directive $directive): int|bool|null
    {
        return $this->items[$directive->value] ?? null;
    }

    public function remove(Directive $directive): self
    {
        unset($this->items[$directive->value]);

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    /**
     * @return list<array{directive: Directive, value: int|bool}>
     */
    public function entries(): array
    {
        $entries = [];

        foreach ($this->items as $directiveValue => $value) {
            $directive = Directive::from($directiveValue);
            $entries[] = [
                'directive' => $directive,
                'value' => $value,
            ];
        }

        return $entries;
    }

    public function clone(): self
    {
        $copy = new self();
        $copy->items = $this->items;

        return $copy;
    }
}
