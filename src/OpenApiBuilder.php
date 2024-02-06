<?php

namespace ChrisVanLier2005\OpenApiGenerator;

use ChrisVanLier2005\OpenApiGenerator\Internal\Endpoint;

class OpenApiBuilder
{
    public function __construct(
        private readonly Generator $generator,
    ) {
        //
    }

    /**
     * Build the virtual endpoint representation to the actual OpenAPI specification.
     *
     * @param string $path
     * @param Endpoint $endpoint
     * @return void
     */
    public function endpointToFile(string $path, Endpoint $endpoint): void
    {

    }
}