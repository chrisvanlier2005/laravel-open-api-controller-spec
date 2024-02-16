<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

class Response implements \JsonSerializable
{
    public function __construct(
        public ?string $description,
        public int $status,
        public Content $content,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            $this->status => [
                'description' => $this->description,
                'content' => $this->content,
            ],
        ];
    }
}