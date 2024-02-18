<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers;

interface MapperSet
{
    /**
     * Retrieve a list of mappers that should be used.
     *
     * @return list<class-string<\ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper>>
     */
    public function __invoke(): array;
}