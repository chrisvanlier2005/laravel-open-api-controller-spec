<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Sets;

use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\DescriptionMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\OperationIdMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\MethodMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\ParameterMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\PathMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\ResponseMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet;

final class ControllerSet implements MapperSet
{
    /**
     * Retrieve a list of mappers that should be used.
     *
     * @return list<class-string<\ChrisVanLier2005\OpenApiGenerator\Mappers\Mapper>>
     */
    public function __invoke(): array
    {
        return [
            MethodMapper::class,
            OperationIdMapper::class,
            DescriptionMapper::class,
            ParameterMapper::class,
            PathMapper::class,
            ResponseMapper::class,
        ];
    }
}