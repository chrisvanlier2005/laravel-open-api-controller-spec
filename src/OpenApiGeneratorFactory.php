<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Mappers\MapperSet;
use PhpParser\ParserFactory;

class OpenApiGeneratorFactory
{
    /**
     * Create an OpenApiGenerator instance for the latest supported PHP version.
     *
     * @return \ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator
     */
    public static function make(array|MapperSet|null $mappers = null): OpenApiGenerator
    {
        return new OpenApiGenerator(
            (new ParserFactory())->createForNewestSupportedVersion(),
            $mappers,
        );
    }
}