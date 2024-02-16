<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

class Schema
{
    public function __construct(
        public string $type,
        public ?string $format,
    ) {
    }
}