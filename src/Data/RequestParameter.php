<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

final class RequestParameter
{
    public function __construct(
        public string $name,
        public string $in,
        public bool $required,
        public string $description,
        public string $type,
        public Schema|Reference $schema,
    ) {
    }
}