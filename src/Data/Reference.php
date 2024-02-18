<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

use JsonSerializable;

class Reference implements JsonSerializable
{
    public function __construct(
        public string $ref,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            '$ref' => $this->ref,
        ];
    }
}