<?php

namespace ChrisVanLier2005\OpenApiGenerator;

class GeneratorOptions
{
    /**
     * Create a new instance of the GeneratorOptions.
     *
     * @param array<array-key, class-string> $only Only use these classes for the generation.
     * @param bool $useResourceRouting Use the resource name for generating endpoints.
     * @return void
     */
    public function __construct(
        public array $only = [],
        public bool $useResourceRouting = true,
    ) {
    }
}