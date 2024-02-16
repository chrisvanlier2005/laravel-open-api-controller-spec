<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use PhpParser\ParserFactory;

final class OpenApiGeneratorFactory
{
    /**
     * Create an OpenApiGenerator instance for the latest supported PHP version.
     *
     * @return \ChrisVanLier2005\OpenApiGenerator\OpenApiGenerator
     */
    public static function make(): OpenApiGenerator
    {
        return new OpenApiGenerator(
            (new ParserFactory())->createForNewestSupportedVersion(),
        );
    }
}