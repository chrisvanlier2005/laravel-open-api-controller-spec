<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

use JsonSerializable;

class Content implements JsonSerializable
{
    public function __construct(
        public string $type,
        public Schema|Reference $schema,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            $this->type => [
                'schema' => $this->schema,
            ],
        ];
    }
}