<?php

namespace FpDbTest\Specifier;

final class SpecifierCollection
{
    private readonly DefaultSpecifier $defaultSpecifier;
    private array $specifiers = [];
    private array $specifierClass = [
        ArraySpecifier::class,
        FloatSpecifier::class,
        IntSpecifier::class,
        IdentSpecifier::class,
    ];

    public function __construct()
    {
        foreach ($this->specifierClass as $class) {
            $specifier = new $class();
            $this->specifiers[$specifier->getMarker()] = $specifier;
        }
        $this->defaultSpecifier = new DefaultSpecifier();
    }

    /**
     * @param string $marker
     * @return AbstractSpecifier
     */
    public function get(string $marker): AbstractSpecifier
    {
        return $this->specifiers[$marker] ?? $this->defaultSpecifier;
    }

    /**
     * @return array
     */
    public function getMarkers(): array
    {
        return array_keys($this->specifiers);
    }
}
