<?php

namespace ChrisVanLier2005\OpenApiGenerator\Mappers\Sets;

use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\OperationIdMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\MethodMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\ParameterMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\PathMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\Controllers\ResponseMapper;
use ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet;

final class ControllerSet implements MapperSet
{
    public function __invoke(): array
    {
        return [
            OperationIdMapper::class,
            MethodMapper::class,
            ParameterMapper::class,
            PathMapper::class,
            ResponseMapper::class,
        ];
    }
}