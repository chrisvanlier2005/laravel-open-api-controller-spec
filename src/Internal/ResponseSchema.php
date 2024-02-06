<?php

namespace ChrisVanLier2005\OpenApiGenerator\Internal;

readonly class ResponseSchema
{
    public function __construct(
        public array $properties,
    ) {
    }
}