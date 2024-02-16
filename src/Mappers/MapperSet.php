<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers;

interface MapperSet
{
    /**
     * Get the mappers for the set.
     *
     * @return array<array-key, class-string<\ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper>>
     */
    public function __invoke(): array;
}