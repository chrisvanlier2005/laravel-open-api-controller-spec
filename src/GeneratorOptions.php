<?php

namespace ChrisVanLier2005\OpenApiGenerator;

readonly class GeneratorOptions
{

    /**
     * Create a new instance of the GeneratorOptions.
     *
     * @param bool $useResourceRouting Use the resource name for generating endpoints.
     * @param string $routeParameterRefPrefix The prefix for the route parameter reference.
     * @param string $responseRefPrefix The prefix for the response reference.
     * @param array<int, string> $validReferenceNamespaces The valid reference namespaces.
     * @return void
     */
    public function __construct(
        public bool $useResourceRouting = true,
        public string $routeParameterRefPrefix = '',
        public string $responseRefPrefix = '',
        public array $validReferenceNamespaces = ['App\\Models\\'],
    ) {

    }
}
