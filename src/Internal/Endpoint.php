<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

final class Endpoint
{
    /**
     * Create an representation of an endpoint.
     *
     * @param string|null $class
     * @param string|null $path
     * @param string|null $method
     * @param string|null $action
     * @param array|null $parameters
     * @param array|null $responses
     */
    public function __construct(
        public ?string $class = null,
        public ?string $path = null,
        public ?string $method = null,
        public ?string $action = null,
        public ?array $parameters = null,
        public ?array $responses = null,
    ) {
        //
    }
}