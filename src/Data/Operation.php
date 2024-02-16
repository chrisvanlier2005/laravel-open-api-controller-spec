<?php

namespace ChrisVanLier2005\OpenApiGenerator\Data;

use Illuminate\Support\Arr;
use JsonSerializable;

final class Operation implements JsonSerializable
{
    /**
     * Create a representation of an endpoint.
     *
     * @param string|null $class
     * @param string|null $classMethod
     * @param string|null $path
     * @param string|null $method
     * @param string|null $operationId
     * @param array<array-key, \ChrisVanLier2005\OpenApiGenerator\Data\RequestParameter|\ChrisVanLier2005\OpenApiGenerator\Data\Reference>|null $parameters
     * @param array<\ChrisVanLier2005\OpenApiGenerator\Data\Response>|null $responses
     */
    public function __construct(
        public ?string $class = null,
        public ?string $classMethod = null,
        public ?string $path = null,
        public ?string $method = null,
        public ?string $operationId = null,
        public ?array $parameters = null,
        public ?array $responses = null,
    ) {
        //
    }

    /**
     * Prepare the instance for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            $this->path => [
                $this->method => [
                    'operationId' => $this->operationId,
                    'description' => null,
                    'parameters' => $this->parameters,
                    'responses' => Arr::mapWithKeys(
                        $this->responses ?? [],
                        fn (Response $response) => $response->jsonSerialize()
                    ),
                ],
            ],
        ];
    }
}