<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

use JsonSerializable;
use function ChrisVanLier2005\OpenApiGenerator\dd;

readonly class Reference implements JsonSerializable
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